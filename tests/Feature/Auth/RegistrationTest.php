<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    // Visit register page to initialize session and CSRF token
    $this->get('/register');
    $token = session('_token');

    $response = $this->post('/register', [
        '_token' => $token,
        'full_name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'nip' => '123456',
        'position' => 'Tester',
        'division' => 'Testing',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
