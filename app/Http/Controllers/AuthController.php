<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle the login process.
     */
    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 6)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $remember = $request->has('remember');
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            RateLimiter::clear($key);

            // Bypass OTP if Admin or already verified
            if ($user->role === 'admin' || !is_null($user->email_verified_at)) {
                Auth::login($user, $remember);
                $request->session()->regenerate();
                
                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/dashboard');
                }

                if (!$user->hasCompletedOnboarding()) {
                    return redirect()->route('onboarding');
                }

                return redirect()->intended('/dashboard');
            }

            return $this->sendOtp($user, $remember);
        }

        RateLimiter::hit($key, 60);

        throw ValidationException::withMessages([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration process.
     */
    public function registerProcess(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            // role defaults to 'free' in the database
        ]);

        return $this->sendOtp($user, false);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Bypass SSL verification for local development (cURL error 60 fix)
            $httpClient = new \GuzzleHttp\Client(['verify' => false]);
            $googleUser = Socialite::driver('google')->setHttpClient($httpClient)->stateless()->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, update google_id if missing
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                
                Auth::login($user, true); // Google is implicitly remembered
                $request->session()->regenerate();
                
                if ($user->role === 'admin') {
                    $response = redirect()->intended('/admin/dashboard');
                } elseif (!$user->hasCompletedOnboarding()) {
                    $response = redirect()->route('onboarding');
                } else {
                    $response = redirect()->intended('/dashboard');
                }
                            
                // Trust this device for 30 days (43200 minutes)
                $response->cookie('trusted_device_user_' . $user->id, true, 43200);

                return $response;
            } else {
                // New User - Register via Google (Email already verified by Google)
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(), // Bypass OTP for Google Users
                ]);
                
                Auth::login($user, true);
                $request->session()->regenerate();
                return redirect()->route('onboarding');
            }
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['email' => 'Gagal login menggunakan Google: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper to generate and send OTP.
     */
    protected function sendOtp($user, $remember)
    {
        $otp = (string) random_int(100000, 999999);
        
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));

        session([
            'otp_user_id' => $user->id, 
            'otp_remember' => $remember,
            'last_otp_requested_at' => now()->timestamp
        ]);

        return redirect('/otp');
    }

    /**
     * Resend OTP.
     */
    public function resendOtp()
    {
        if (!session()->has('otp_user_id')) {
            return redirect('/login');
        }

        $lastRequestedAt = session('last_otp_requested_at', 0);
        if (now()->timestamp - $lastRequestedAt < 60) {
            return back()->withErrors(['otp' => 'Tunggu 1 menit sebelum meminta kode baru.']);
        }

        $user = User::find(session('otp_user_id'));
        if (!$user) {
            return redirect('/login');
        }

        // Send OTP again (this inherently overwrites the old OTP)
        return $this->sendOtp($user, session('otp_remember', false))->with('status', 'Kode OTP baru telah dikirim.');
    }

    /**
     * Show OTP verification page.
     */
    public function showOtp()
    {
        if (!session()->has('otp_user_id')) {
            return redirect('/login');
        }
        $user = User::find(session('otp_user_id'));
        return view('auth.otp', ['email' => $user->email]);
    }

    /**
     * Verify OTP and Login.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if (!session()->has('otp_user_id')) {
            return redirect('/login');
        }

        $user = User::find(session('otp_user_id'));

        if ($user && $user->otp_code === $request->otp && $user->otp_expires_at > now()) {
            // Valid OTP
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = now(); // Mark as verified
            $user->save();

            $rememberCookie = session('otp_remember', false);
            Auth::login($user, $rememberCookie);
            $request->session()->regenerate();
            session()->forget(['otp_user_id', 'otp_remember']);

            if ($user->role === 'admin') {
                $response = redirect()->intended('/admin/dashboard');
            } elseif (!$user->hasCompletedOnboarding()) {
                $response = redirect()->route('onboarding');
            } else {
                $response = redirect()->intended('/dashboard');
            }

            return $response;
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
    }
}
