<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalUsers = \App\Models\User::count();
        $totalUsers = $totalUsers > 0 ? $totalUsers : 1;

        $sync = \App\Services\BadgeService::syncUserBadges($user);
        $badgesWithCounts = $sync['badges'];
        $userBadgeIds = $sync['userBadgeIds'];
        $totalBadgeCount = $badgesWithCounts->count();

        // Separate user's earned badges and unearned badges
        $earnedBadges = $badgesWithCounts->whereIn('id', $userBadgeIds)->sortBy('users_count');
        $unearnedBadges = $badgesWithCounts->whereNotIn('id', $userBadgeIds)->sortBy('users_count');

        // Take up to 3 rarest earned badges, fill the rest with rarest unearned badges
        $displayBadges = $earnedBadges->take(3);
        if ($displayBadges->count() < 3) {
            $needed = 3 - $displayBadges->count();
            $displayBadges = $displayBadges->merge($unearnedBadges->take($needed));
        }

        return view('users.settings', compact('displayBadges', 'userBadgeIds', 'totalUsers', 'totalBadgeCount'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Handle AJAX Instant Avatar Upload
        if ($request->wantsJson() || $request->ajax()) {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            ]);

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui!',
                'avatar_url' => Storage::url($path),
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'province' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'in:id,en'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->province = self::normalizeProvince($request->province);
        if ($request->has('language')) {
            $user->language = $request->language;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Pengaturan profil berhasil disimpan.');
    }

    public static function normalizeProvince(?string $input): ?string
    {
        if (!$input) return null;

        $provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi', 
            'Sumatera Selatan', 'Bangka Belitung', 'Bengkulu', 'Lampung', 'DKI Jakarta', 
            'Jawa Barat', 'Banten', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Bali', 
            'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah', 
            'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara', 
            'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara', 
            'Maluku', 'Maluku Utara', 'Papua Barat', 'Papua'
        ];

        $map = [
            'north sumatra' => 'Sumatera Utara',
            'north sumatera' => 'Sumatera Utara',
            'sumatra utara' => 'Sumatera Utara',
            'sumatra' => 'Sumatera Utara',
            'medan' => 'Sumatera Utara',
            'kota medan' => 'Sumatera Utara',
            'percut' => 'Sumatera Utara',
            'deli serdang' => 'Sumatera Utara',
            'west java' => 'Jawa Barat',
            'bandung' => 'Jawa Barat',
            'central java' => 'Jawa Tengah',
            'semarang' => 'Jawa Tengah',
            'east java' => 'Jawa Timur',
            'surabaya' => 'Jawa Timur',
            'jakarta' => 'DKI Jakarta',
            'dki jakarta' => 'DKI Jakarta',
            'yogyakarta' => 'DI Yogyakarta',
            'jogja' => 'DI Yogyakarta',
            'west sumatra' => 'Sumatera Barat',
            'south sumatra' => 'Sumatera Selatan',
            'west kalimantan' => 'Kalimantan Barat',
            'central kalimantan' => 'Kalimantan Tengah',
            'south kalimantan' => 'Kalimantan Selatan',
            'east kalimantan' => 'Kalimantan Timur',
            'north kalimantan' => 'Kalimantan Utara',
            'north sulawesi' => 'Sulawesi Utara',
            'central sulawesi' => 'Sulawesi Tengah',
            'west sulawesi' => 'Sulawesi Barat',
            'south sulawesi' => 'Sulawesi Selatan',
            'southeast sulawesi' => 'Sulawesi Tenggara',
            'west nusa tenggara' => 'Nusa Tenggara Barat',
            'east nusa tenggara' => 'Nusa Tenggara Timur',
            'north maluku' => 'Maluku Utara',
            'west papua' => 'Papua Barat',
            'papua' => 'Papua',
        ];

        $lower = strtolower(trim($input));
        
        foreach ($provinces as $prov) {
            if (strtolower($prov) === $lower) {
                return $prov;
            }
        }

        foreach ($map as $key => $val) {
            if (str_contains($lower, $key) || str_contains($key, $lower)) {
                return $val;
            }
        }

        foreach ($provinces as $prov) {
            if (str_contains($lower, strtolower($prov)) || str_contains(strtolower($prov), $lower)) {
                return $prov;
            }
        }

        return $input;
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
        ]);

        if ($request->has('email_notifications')) {
            $user->email_notifications = $request->email_notifications;
        }

        if ($request->has('push_notifications')) {
            $user->push_notifications = $request->push_notifications;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Pengaturan notifikasi berhasil disimpan.']);
    }

    public function showPassword()
    {
        return view('users.settings-password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'old_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect('/settings')->with('success', 'Password berhasil diperbarui.');
    }

    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        // Optional: you can validate password here if you want to require it for deletion.
        // But for now, we just delete.
        Auth::logout();
        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    }
}
