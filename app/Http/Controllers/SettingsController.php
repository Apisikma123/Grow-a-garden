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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'province' => ['nullable', 'string', 'max:100'],
            'language' => ['required', 'string', 'in:id,en'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->province = $request->province;
        $user->language = $request->language;

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
