<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            Log::info('Google OAuth callback received', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);

            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'email_verified_at' => now(), // Mark email as verified since it's from Google
                    ]);
                    Log::info('Linked Google account to existing user', ['user_id' => $user->id]);
                } else {
                    // Create new user
                    $user = User::create([
                        'full_name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => Hash::make(Str::random(24)), // Random password since they'll use Google login
                        'email_verified_at' => now(), // Email is verified by Google
                        'role' => 'participant', // Default role
                    ]);
                    Log::info('Created new user from Google OAuth', ['user_id' => $user->id]);
                }
            }

            // Log the user in
            Auth::shouldUse('web');
            Auth::guard('web')->login($user);
            session()->regenerate();
            session()->save();
            Log::info('User logged in via Google OAuth', [
                'user_id' => $user->id,
                'role' => $user->role,
                'is_authenticated' => Auth::guard('web')->check(),
                'session_id' => session()->getId(),
                'auth_user' => Auth::user() ? Auth::user()->id : null
            ]);

            // Authentication successful - redirect to dashboard
            Log::info('Authentication successful, redirecting to dashboard', [
                'user_id' => $user->id,
                'role' => $user->role,
                'redirect_url' => $user->role === 'admin' ? route('admin.dashboard') : route('participant.dashboard')
            ]);

            return redirect()->to(
                $user->role === 'admin'
                    ? route('admin.dashboard')
                    : route('participant.dashboard')
            )->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            Log::error('Google OAuth error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->withErrors(['google' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }
    }
}
