<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $remember = $request->has('remember');

        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Check if device is already trusted
            if ($request->cookie('trusted_device_user_' . $user->id)) {
                Auth::login($user, $remember);
                $request->session()->regenerate();
                
                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/dashboard');
                }
                return redirect()->intended('/dashboard');
            }

            return $this->sendOtp($user, $remember);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
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
            
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(16)), // Assign random password
                ]
            );
            
            // Check if device is already trusted
            if ($request->cookie('trusted_device_user_' . $user->id)) {
                Auth::login($user, true); // Google is implicitly remembered
                
                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/dashboard');
                }
                return redirect()->intended('/dashboard');
            }

            return $this->sendOtp($user, true); // Assume remember me for Google
            
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

        session(['otp_user_id' => $user->id, 'otp_remember' => $remember]);

        return redirect('/otp');
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
            $user->save();

            $rememberCookie = session('otp_remember', false);
            Auth::login($user, $rememberCookie);
            $request->session()->regenerate();
            session()->forget(['otp_user_id', 'otp_remember']);

            $response = ($user->role === 'admin') 
                        ? redirect()->intended('/admin/dashboard') 
                        : redirect()->intended('/dashboard');

            if ($rememberCookie) {
                // Trust this device for 30 days (43200 minutes)
                $response->cookie('trusted_device_user_' . $user->id, true, 43200);
            }

            return $response;
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
    }
}
