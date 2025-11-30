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
        // Make sure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->withErrors(['calendar' => 'Anda harus login terlebih dahulu.']);
        }

        $clientId = config('services.google.client_id');

        // FIX: Use proper redirect URI without encoding issues
        // Use config value if set, otherwise construct from APP_URL
        $redirectUri = config('services.google.calendar_redirect')
            ?: (config('app.url') ?: env('APP_URL')) . '/google-calendar/callback';

        // Make sure redirect URI uses HTTPS (required by Google OAuth)
        if (config('app.env') === 'production' && !str_starts_with($redirectUri, 'https://')) {
            $redirectUri = str_replace('http://', 'https://', $redirectUri);
        }

        $scope = 'https://www.googleapis.com/auth/calendar';
        // Generate state with user_id encoded for later verification
        $stateData = base64_encode(json_encode([
            'user_id' => Auth::id(),
            'timestamp' => time(),
        ]));

        // Prefix the state string so callback routing can distinguish calendar auth
        $calendarState = 'calendar_auth_' . $stateData;

        // FIX: Build query parameters properly to avoid encoding issues with Firefox
        $authParams = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $scope,
            'response_type' => 'code',
            'access_type' => 'offline',
            'prompt' => 'select_account',
            'state' => $calendarState,
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($authParams, '', '&', PHP_QUERY_RFC3986);

        Log::info('Google Calendar auth URL generated', [
            'user_id' => Auth::id(),
            'redirect_uri' => $redirectUri,
            'browser_user_agent' => request()->userAgent(),
        ]);

        return redirect($authUrl);
    }

    /**
     * Handle Google OAuth callback for Calendar
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Handle OAuth errors first: if an error is present, handle gracefully
            if ($request->has('error')) {
                // If user is already logged in, return to dashboard with message
                if (Auth::check()) {
                    $errorMessages = [
                        'access_denied' => 'Anda menolak akses ke Google Calendar.',
                        'server_error' => 'Server Google mengalami masalah. Silakan coba lagi.',
                        'temporarily_unavailable' => 'Google OAuth sedang tidak tersedia. Silakan coba lagi nanti.',
                    ];

                    $message = $errorMessages[$request->error] ?? 'Gagal menghubungkan Google Calendar.';
                    return redirect()->route('participant.dashboard')->withErrors(['calendar' => $message]);
                }

                // If user is not logged in, and a state is present, we may try to reconstruct session
                if (!$request->has('state')) {
                    Log::warning('Google OAuth callback missing state parameter');
                    return redirect()->route('login')
                        ->withErrors(['calendar' => 'Keamanan callback gagal: state tidak ditemukan.']);
                }
            }

            try {
                Log::debug('GoogleCalendarAuthController: raw state', ['state' => $request->state]);
                $stateString = $request->state;
                if (str_starts_with($stateString, 'calendar_auth_')) {
                    $stateString = substr($stateString, strlen('calendar_auth_'));
                }
                $stateData = json_decode(base64_decode($stateString, true), true);
                if (!$stateData || !isset($stateData['user_id'])) {
                    throw new \Exception('Invalid state data');
                }
                $userId = $stateData['user_id'];
            } catch (\Exception $e) {
                Log::warning('Google OAuth state decode failed', [
                    'state' => substr($request->state, 0, 50),
                    'error' => $e->getMessage(),
                ]);
                if (Auth::check()) {
                    return redirect()->route('participant.dashboard')
                        ->withErrors(['calendar' => 'Keamanan callback gagal: state tidak valid.']);
                }
                return redirect()->route('login')
                    ->withErrors(['calendar' => 'Keamanan callback gagal: state tidak valid.']);
            }

            // Restore user from state data
            Auth::loginUsingId($userId, true);

            // Ensure user is authenticated before continuing
            if (!Auth::check()) {
                Log::warning('User not authenticated during Google callback', ['user_id' => $userId]);
                return redirect()->route('login')
                    ->withErrors(['calendar' => 'Sesi telah berakhir. Silakan login kembali.']);
            }

            // FIX: Better logging for debugging Firefox issues
            $userAgent = request()->userAgent();
            $isBrowserSpecific = [
                'firefox' => stripos($userAgent, 'firefox') !== false,
                'chrome' => stripos($userAgent, 'chrome') !== false,
                'edge' => stripos($userAgent, 'edg') !== false,
            ];

            Log::info('Google Calendar callback started', [
                'user_id' => Auth::id(),
                'has_code' => $request->has('code'),
                'has_error' => $request->has('error'),
                'error' => $request->error,
                'browser' => $isBrowserSpecific,
                'query_params' => array_keys($request->query()),
            ]);

            // Check for OAuth errors
            if ($request->has('error')) {
                Log::warning('Google OAuth error returned', [
                    'user_id' => Auth::id(),
                    'error' => $request->error,
                    'error_description' => $request->error_description,
                    'browser' => $isBrowserSpecific,
                ]);

                $errorMessages = [
                    'access_denied' => 'Anda menolak akses ke Google Calendar.',
                    'server_error' => 'Server Google mengalami masalah. Silakan coba lagi.',
                    'temporarily_unavailable' => 'Google OAuth sedang tidak tersedia. Silakan coba lagi nanti.',
                ];

                $message = $errorMessages[$request->error] ?? 'Gagal menghubungkan Google Calendar.';
                return redirect()->route('participant.dashboard')
                    ->withErrors(['calendar' => $message]);
            }

            if (!$request->has('code')) {
                Log::error('No authorization code in callback', [
                    'user_id' => Auth::id(),
                    'params' => $request->query(),
                ]);
                return redirect()->route('participant.dashboard')
                    ->withErrors(['calendar' => 'Authorization code tidak ditemukan.']);
            }

            // FIX: Set proper redirect URI matching the one used in redirectToGoogle()
            $redirectUri = config('services.google.calendar_redirect')
                ?: env('APP_URL') . '/google-calendar/callback';

            if (config('app.env') === 'production' && !str_starts_with($redirectUri, 'https://')) {
                $redirectUri = str_replace('http://', 'https://', $redirectUri);
            }

            $client = new \Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri($redirectUri);
            // Removed setApprovalPrompt('force') to prevent double consent screens

            Log::info('Google client configured for token exchange', [
                'redirect_uri' => $redirectUri,
                'code_length' => strlen($request->code),
            ]);

            try {
                $token = $client->fetchAccessTokenWithAuthCode($request->code);
            } catch (\Exception $tokenError) {
                Log::error('Token exchange failed', [
                    'user_id' => Auth::id(),
                    'error' => $tokenError->getMessage(),
                    'browser' => $isBrowserSpecific,
                ]);
                // FIX: More detailed error handling for Firefox issues
                if (stripos($tokenError->getMessage(), 'invalid_grant') !== false) {
                    return redirect()->route('participant.dashboard')
                        ->withErrors(['calendar' => 'Kode autorisasi telah kadaluarsa. Silakan coba lagi.']);
                }
                throw $tokenError;
            }

            $client->setAccessToken($token);

            $user = Auth::user();

            // Store tokens - use user's email as calendar ID (most common case)
            $user->update([
                'google_access_token' => $token['access_token'],
                'google_refresh_token' => $token['refresh_token'] ?? null,
                'google_token_expires_at' => now()->addSeconds($token['expires_in'] ?? 3600),
                'google_calendar_id' => $user->email,
            ]);

            Log::info('Google Calendar authorized for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'browser' => $isBrowserSpecific,
                'has_refresh_token' => !empty($token['refresh_token']),
            ]);

            return redirect()->route('participant.dashboard')
                ->with('success', 'Google Calendar berhasil dihubungkan! Event akan otomatis ditambahkan ke calendar Anda.');
        } catch (\Exception $e) {
            Log::error('Google Calendar OAuth error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'browser_agent' => request()->userAgent(),
            ]);

            // FIX: More specific error messages
            $errorMsg = 'Gagal menghubungkan Google Calendar. Silakan coba lagi.';
            if (stripos($e->getMessage(), 'curl') !== false || stripos($e->getMessage(), 'connection') !== false) {
                $errorMsg = 'Koneksi ke Google gagal. Periksa koneksi internet Anda.';
            }

            return redirect()->route('participant.dashboard')
                ->withErrors(['calendar' => $errorMsg]);
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
