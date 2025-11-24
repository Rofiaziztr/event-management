<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    // Run migrations and refresh the database for each test class
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    // NOTE: Removed global middleware disabling to ensure session and web middleware
    // are available during tests. Individual tests can opt-out of specific
    // middleware (e.g., CSRF) using withoutMiddleware(\Illuminate\Foundation\Http\
    // Middleware\VerifyCsrfToken::class) when needed.
}
