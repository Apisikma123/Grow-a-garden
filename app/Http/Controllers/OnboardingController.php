<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Garden;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\BadgeService;
use App\Services\AutopilotService;
use Carbon\Carbon;

class OnboardingController extends Controller
{
    private array $plans = [
        'subur' => [
            'role' => 'pro',
            'label' => 'Paket Subur',
            'monthly_price' => 29000,
            'yearly_price' => 199000,
        ],
        'pro' => [
            'role' => 'premium',
            'label' => 'Panen Raya (Premium)',
            'monthly_price' => 99000,
            'yearly_price' => 799000,
        ],
    ];

    private function cleanLocationName(?string $location): ?string
    {
        if (!$location) return null;
        $cleaned = trim(preg_replace('/\s*\([\d\.\,\s\-]+\)/i', '', $location));
        return ($cleaned === 'Lokasi Terdeteksi' || $cleaned === 'Kota Terdeteksi' || !$cleaned) ? 'Lokasi Kebun' : $cleaned;
    }

    /**
     * Show the Onboarding Wizard.
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        // If user already completed onboarding and has at least 1 garden (and no ?force=1), redirect to dashboard
        if ($user->hasCompletedOnboarding() && $user->gardens()->count() > 0 && !$request->has('force')) {
            return redirect('/dashboard');
        }

        return view('auth.onboarding', [
            'user' => $user,
        ]);
    }

    /**
     * Complete the onboarding process and save questionnaire results.
     */
    public function complete(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'garden_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gardening_experience' => 'nullable|string|max:50',
            'gardening_scale' => 'nullable|string|max:50',
            'gardening_goal' => 'nullable|string|max:100',
            'selected_plan' => 'nullable|string|in:free,subur,pro',
            'billing_cycle' => 'nullable|string|in:monthly,yearly',
        ]);

        $user = Auth::user();
        $cleanedLocation = $this->cleanLocationName($request->location);

        // 1. Update User Profile & Name
        $user->update([
            'name' => $request->user_name,
            'gardening_experience' => $request->gardening_experience ?? 'beginner',
            'gardening_scale' => $request->gardening_scale ?? '10-50',
            'gardening_goal' => $request->gardening_goal ?? 'automation',
            'province' => $cleanedLocation ?? $user->province,
            'onboarding_completed_at' => now(),
        ]);

        // 2. Create or Update Initial Garden
        $garden = $user->gardens()->first();
        if (!$garden) {
            $garden = Garden::create([
                'user_id' => $user->id,
                'name' => $request->garden_name,
                'location_name' => $cleanedLocation,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        } else {
            $garden->update([
                'name' => $request->garden_name,
                'location_name' => $cleanedLocation,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }

        $selectedPlan = $request->input('selected_plan', 'free');
        $billingCycle = $request->input('billing_cycle', 'yearly');
        $redirectUrl = '/dashboard';

        // 3. Handle Plan Selection
        if (in_array($selectedPlan, ['subur', 'pro'])) {
            $planConfig = $this->plans[$selectedPlan];
            $amount = $billingCycle === 'yearly' ? $planConfig['yearly_price'] : $planConfig['monthly_price'];
            $validUntil = $billingCycle === 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth();

            // Cancel any old subscription
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'canceled']);

            // Create active subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_name' => $selectedPlan,
                'billing_cycle' => $billingCycle,
                'status' => 'active',
                'valid_until' => $validUntil,
            ]);

            // Dev bypass transaction
            Transaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $amount,
                'payment_method' => 'dev_bypass',
                'status' => 'success',
            ]);

            // Update user role
            $user->update(['role' => $planConfig['role']]);

            // Auto-generate care tasks for autopilot
            $autopilot = new AutopilotService();
            $autopilot->generateForUser($user);
        }

        // 4. Sync badges (auto-awards 'Pekebun Pertama' badge)
        $sync = BadgeService::syncUserBadges($user);
        $newBadge = null;
        if (!empty($sync['newlyAwardedIds'])) {
            $badgeModel = \App\Models\Badge::find($sync['newlyAwardedIds'][0]);
            if ($badgeModel) {
                $newBadge = [
                    'name' => $badgeModel->name,
                    'description' => $badgeModel->description,
                    'icon_url' => $badgeModel->icon_url,
                ];
                session()->flash('new_badge', $newBadge);
            }
        }

        session()->flash('success', 'Selamat datang di Grow a Garden! Kebun pertama Anda siap dirawat.');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi kebun berhasil diselesaikan!',
                'redirect_url' => $redirectUrl,
                'garden' => $garden,
                'user' => $user->fresh(),
                'new_badge' => $newBadge,
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Skip onboarding with sensible defaults.
     */
    public function skip(Request $request)
    {
        $user = Auth::user();

        // If user provided a name before skipping, save it
        if ($request->filled('user_name')) {
            $user->name = $request->user_name;
        }

        $user->gardening_experience = $user->gardening_experience ?? 'beginner';
        $user->gardening_scale = $user->gardening_scale ?? '1-10';
        $user->gardening_goal = $user->gardening_goal ?? 'automation';
        $user->onboarding_completed_at = now();
        $user->save();

        // Create default garden if none exists
        if ($user->gardens()->count() === 0) {
            $garden = Garden::create([
                'user_id' => $user->id,
                'name' => 'Kebun Pertama ' . ($user->name ? explode(' ', $user->name)[0] : 'Saya'),
                'location_name' => $user->province ?? 'Jakarta Selatan, DKI Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
            ]);

            BadgeService::syncUserBadges($user);
        }

        session()->flash('success', 'Selamat datang! Anda dapat mengatur preferensi dan profil kebun kapan saja di menu Pengaturan.');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect_url' => '/dashboard',
            ]);
        }

        return redirect('/dashboard');
    }
}
