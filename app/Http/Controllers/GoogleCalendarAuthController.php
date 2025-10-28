<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleCalendarService;
use App\Models\EventCalendarSync;

class GoogleCalendarAuthController extends Controller
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Redirect to Google OAuth for Calendar access
     */
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');
        $redirectUri = urlencode(config('services.google.calendar_redirect') ?: env('APP_URL') . '/google-calendar/callback');
        $scope = urlencode('https://www.googleapis.com/auth/calendar');
        $state = 'calendar_auth_' . Auth::id();

        $authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" .
            "client_id={$clientId}&" .
            "redirect_uri={$redirectUri}&" .
            "scope={$scope}&" .
            "response_type=code&" .
            "access_type=offline&" .
            "prompt=consent&" .
            "state={$state}";

        Log::info('Google Calendar auth URL generated', [
            'user_id' => Auth::id(),
            'auth_url' => $authUrl
        ]);

        return redirect($authUrl);
    }

    /**
     * Handle Google OAuth callback for Calendar
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            Log::info('Google Calendar callback started', [
                'has_code' => $request->has('code'),
                'code' => $request->code ? substr($request->code, 0, 10) . '...' : null,
                'error' => $request->error,
                'state' => $request->state
            ]);

            $client = new \Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri(config('services.google.calendar_redirect') ?: env('APP_URL') . '/google-calendar/callback');

            Log::info('Google client configured', [
                'client_id' => config('services.google.client_id') ? 'set' : 'not set',
                'client_secret' => config('services.google.client_secret') ? 'set' : 'not set',
                'redirect_uri' => config('services.google.calendar_redirect')
            ]);

            if ($request->has('code')) {
                $token = $client->fetchAccessTokenWithAuthCode($request->code);
                $client->setAccessToken($token);

                $user = Auth::user();

                // Store tokens - use user's email as calendar ID (most common case)
                $user->update([
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'] ?? null,
                    'google_token_expires_at' => now()->addSeconds($token['expires_in']),
                    'google_calendar_id' => $user->email,
                ]);

                Log::info('Google Calendar authorized for user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'google_calendar_id' => $user->google_calendar_id
                ]);

                return redirect()->route('participant.dashboard')
                    ->with('success', 'Google Calendar berhasil dihubungkan! Event akan otomatis ditambahkan ke calendar Anda.');
            }

            return redirect()->route('participant.dashboard')
                ->withErrors(['calendar' => 'Authorization code tidak ditemukan.']);
        } catch (\Exception $e) {
            Log::error('Google Calendar OAuth error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('participant.dashboard')
                ->withErrors(['calendar' => 'Gagal menghubungkan Google Calendar. Silakan coba lagi.']);
        }
    }
    /**
     * Redirect to Google Account permissions page for revoking access
     * Note: This method now provides guidance rather than direct API revocation
     * since Google doesn't allow programmatic revocation of OAuth access
     */
    public function revokeAccess()
    {
        $user = Auth::user();

        // If user has access, clear our local tokens first
        if ($user->hasGoogleCalendarAccess()) {
            // Clear our local tokens first
            $user->update([
                'google_access_token' => null,
                'google_refresh_token' => null,
                'google_token_expires_at' => null,
                'google_calendar_id' => null,
            ]);

            // Clean up sync records
            EventCalendarSync::where('user_id', $user->id)->delete();

            Log::info('Local tokens cleared, user guided to manual revoke process', [
                'user_id' => $user->id
            ]);

            // Redirect back with success message about local cleanup
            return redirect()->route('participant.dashboard')
                ->with('success', 'Token lokal telah dibersihkan. Silakan ikuti panduan untuk mencabut akses Google Calendar secara manual.');
        }

        // If no access, just redirect back
        return redirect()->route('participant.dashboard')
            ->with('info', 'Tidak ada koneksi Google Calendar yang perlu diputuskan.');
    }

    /**
     * Check calendar authorization status
     */
    public function status()
    {
        $user = Auth::user();

        return response()->json([
            'authorized' => $user->hasGoogleCalendarAccess(),
            'calendar_id' => $user->google_calendar_id,
            'token_expires_at' => $user->google_token_expires_at,
        ]);
    }

    /**
     * Validate Google Calendar access and refresh status
     */
    public function validateAccess(Request $request)
    {
        $user = Auth::user();

        try {
            $hasValidAccess = $user->hasValidGoogleCalendarAccess();

            if ($request->expectsJson() || $request->ajax()) {
                // Return JSON for AJAX requests
                return response()->json([
                    'success' => $hasValidAccess,
                    'message' => $hasValidAccess
                        ? 'Koneksi Google Calendar Anda masih aktif dan valid.'
                        : 'Koneksi Google Calendar telah diputus atau tidak valid.',
                    'has_access' => $hasValidAccess
                ]);
            } else {
                // Return redirects for regular requests
                if ($hasValidAccess) {
                    return redirect()->route('participant.dashboard')
                        ->with('success', 'Koneksi Google Calendar Anda masih aktif dan valid.');
                } else {
                    return redirect()->route('participant.dashboard')
                        ->with('info', 'Koneksi Google Calendar telah diputus atau tidak valid. Silakan hubungkan kembali jika diperlukan.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error validating Google Calendar access', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memeriksa status koneksi Google Calendar.',
                    'error' => $e->getMessage()
                ], 500);
            } else {
                return redirect()->route('participant.dashboard')
                    ->with('error', 'Terjadi kesalahan saat memeriksa status koneksi Google Calendar.');
            }
        }
    }
}
