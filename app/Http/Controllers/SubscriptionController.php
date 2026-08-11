<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\AutopilotService;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Plan configuration — pricing and role mapping.
     */
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

    /**
     * Process subscription (Dev Mode — instant success, no real payment).
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:subur,pro',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $user = Auth::user();
        $planKey = $request->plan;
        $billingCycle = $request->billing_cycle;
        $planConfig = $this->plans[$planKey];

        // Check if user is upgrading from Subur (pro) to Panen Raya (premium)
        $isUpgrade = ($user->role === 'pro' && $planKey === 'pro');
        $proConfig = $this->plans['subur'];

        $baseAmount = $billingCycle === 'yearly' ? $planConfig['yearly_price'] : $planConfig['monthly_price'];
        $deduction = $isUpgrade ? ($billingCycle === 'yearly' ? $proConfig['yearly_price'] : $proConfig['monthly_price']) : 0;
        $amount = max(0, $baseAmount - $deduction);

        $validUntil = $billingCycle === 'yearly'
            ? Carbon::now()->addYear()
            : Carbon::now()->addMonth();

        // Cancel any existing active subscription first
        Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'canceled']);

        // Create new subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => $planKey,
            'billing_cycle' => $billingCycle,
            'status' => 'active',
            'valid_until' => $validUntil,
        ]);

        // Create transaction record (dev bypass — instant success)
        Transaction::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $amount,
            'payment_method' => 'dev_bypass',
            'status' => 'success',
        ]);

        // Update user role
        $user->update(['role' => $planConfig['role']]);

        // Sync user badges (auto-award subscription badges for new role)
        \App\Services\BadgeService::syncUserBadges($user);

        // Generate care tasks for active plants
        $autopilot = new AutopilotService();
        $tasksGenerated = $autopilot->generateForUser($user);

        return response()->json([
            'success' => true,
            'message' => "Berhasil berlangganan {$planConfig['label']}!",
            'subscription' => [
                'plan' => $planKey,
                'plan_label' => $planConfig['label'],
                'billing_cycle' => $billingCycle,
                'valid_until' => $validUntil->format('d M Y'),
                'amount' => $amount,
            ],
            'autopilot_tasks_generated' => $tasksGenerated,
            'new_role' => $planConfig['role'],
        ]);
    }

    /**
     * Cancel subscription and downgrade to free.
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();

        // Cancel active subscription
        $canceled = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'canceled']);

        if ($canceled === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada langganan aktif untuk dibatalkan.',
            ], 404);
        }

        // Downgrade to free
        $user->update(['role' => 'free']);

        // Sync user badges (detach subscription badges)
        \App\Services\BadgeService::syncUserBadges($user);

        return response()->json([
            'success' => true,
            'message' => 'Langganan berhasil dibatalkan. Anda kembali ke Paket Bibit (Gratis).',
        ]);
    }

    /**
     * Get current subscription status.
     */
    public function status()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'has_subscription' => false,
                'plan' => 'free',
                'plan_label' => 'Bibit (Gratis)',
                'role' => $user->role,
            ]);
        }

        $planConfig = $this->plans[$subscription->plan_name] ?? null;

        return response()->json([
            'has_subscription' => true,
            'plan' => $subscription->plan_name,
            'plan_label' => $planConfig['label'] ?? $subscription->plan_name,
            'billing_cycle' => $subscription->billing_cycle,
            'valid_until' => $subscription->valid_until->format('d M Y'),
            'valid_until_iso' => $subscription->valid_until->toIso8601String(),
            'days_remaining' => (int) now()->diffInDays($subscription->valid_until, false),
            'role' => $user->role,
            'status' => $subscription->status,
        ]);
    }
}
