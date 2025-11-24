<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GoogleCalendarAuthFixTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Database will be created via migrations
    }

    /**
     * Test: OAuth redirect URL is properly formatted for all browsers
     * FIX: Ensure http_build_query with PHP_QUERY_RFC3986 doesn't break Firefox
     */
    public function test_redirect_to_google_uses_proper_url_encoding()
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $response = $this->get(route('google-calendar.auth'));

        // Should redirect to Google OAuth
        $response->assertRedirect();
        $location = $response->headers->get('location');

        // Verify URL structure
        $this->assertStringContainsString('https://accounts.google.com/o/oauth2/v2/auth', $location);
        $this->assertStringContainsString('client_id=', $location);
        $this->assertStringContainsString('redirect_uri=', $location);
        $this->assertStringContainsString('scope=', $location);
        $this->assertStringContainsString('response_type=code', $location);
        $this->assertStringContainsString('access_type=offline', $location);

        // Verify URL is NOT double-encoded (Firefox issue)
        $this->assertStringNotContainsString('%25', $location); // %25 = % sign encoded
    }

    /**
     * Test: Redirect URI in both redirect and callback match
     * FIX: Prevents redirect_uri mismatch errors
     */
    public function test_redirect_uri_consistency()
    {
        config(['services.google.calendar_redirect' => 'http://localhost:8000/google-calendar/callback']);

        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $response = $this->get(route('google-calendar.auth'));
        $location = $response->headers->get('location');

        // Extract redirect_uri from URL
        parse_str(parse_url($location, PHP_URL_QUERY), $params);
        $redirectUri = $params['redirect_uri'] ?? null;

        // Should match config
        $this->assertEquals('http://localhost:8000/google-calendar/callback', $redirectUri);
    }

    /**
     * Test: Proper error handling for OAuth errors
     * FIX: Handle access_denied, server_error, etc. gracefully
     */
    public function test_handles_oauth_access_denied_error()
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $state = 'calendar_auth_' . base64_encode(json_encode(['user_id' => $user->id, 'timestamp' => time()]));

        $response = $this->get(route('google-calendar.callback') . '?' . http_build_query([
            'error' => 'access_denied',
            'error_description' => 'The user denied access to your application',
            'state' => $state,
        ]));

        $response->assertRedirect(route('participant.dashboard'));
        $this->assertTrue(session('errors')?->has('calendar'));
        $errorMsg = session('errors')->get('calendar')[0] ?? '';
        $this->assertStringContainsString('menolak', strtolower($errorMsg));
    }

    /**
     * Test: Handles missing authorization code
     */
    public function test_handles_missing_authorization_code()
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $state = 'calendar_auth_' . base64_encode(json_encode(['user_id' => $user->id, 'timestamp' => time()]));

        $response = $this->get(route('google-calendar.callback') . '?' . http_build_query(['state' => $state]));

        $response->assertRedirect(route('participant.dashboard'));
        $this->assertTrue(session('errors')?->has('calendar'));
        $errorMsg = session('errors')->get('calendar')[0] ?? '';
        $this->assertStringContainsString('code', strtolower($errorMsg));
    }

    /**
     * Test: Firefox user agent detection in logs
     */
    public function test_firefox_browser_detection_in_logs()
    {
        Log::spy();

        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $headers = ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0'];
        $this->get(route('google-calendar.auth'), $headers);

        Log::shouldHaveReceived('info')
            ->withArgs(
                fn($message) =>
                $message === 'Google Calendar auth URL generated'
            );
    }

    /**
     * Test: URL query parameters are properly encoded
     */
    public function test_url_query_parameters_properly_encoded()
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $response = $this->get(route('google-calendar.auth'));
        $location = $response->headers->get('location');

        // Parse the URL
        parse_str(parse_url($location, PHP_URL_QUERY), $params);

        // All parameters should be present
        $this->assertArrayHasKey('client_id', $params);
        $this->assertArrayHasKey('redirect_uri', $params);
        $this->assertArrayHasKey('scope', $params);
        $this->assertArrayHasKey('response_type', $params);
        $this->assertArrayHasKey('access_type', $params);
        $this->assertArrayHasKey('state', $params);

        // Verify values
        $this->assertEquals('code', $params['response_type']);
        $this->assertEquals('offline', $params['access_type']);
        $this->assertStringContainsString('calendar', $params['scope']);
    }

    /**
     * Test: HTTPS redirect URI in production
     */
    public function test_https_redirect_uri_in_production()
    {
        config(['app.env' => 'production']);
        config(['app.url' => 'http://example.com']); // Should be converted to https
        config(['services.google.calendar_redirect' => null]); // Force default

        /** @var User $user */
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $response = $this->get(route('google-calendar.auth'));
        $location = $response->headers->get('location');

        parse_str(parse_url($location, PHP_URL_QUERY), $params);

        // Should use HTTPS
        $this->assertStringStartsWith('https://', $params['redirect_uri']);
    }
}
