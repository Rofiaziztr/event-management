<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();
    // Visit the login page first to initialize the session and CSRF token.
    $this->get('/login');
    $token = session('_token');

    $response = $this->post('/login', [
        '_token' => $token,
        'login' => $user->email,
        'password' => 'password',
    ]);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();
    // Ensure a session and CSRF token exist.
    $this->get('/login');
    $token = session('_token');

    $this->post('/login', [
        '_token' => $token,
        'login' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    // Ensure a session and CSRF token exist for the POST logout.
    $this->actingAs($user)->get('/');
    $token = session('_token');
    $response = $this->actingAs($user)->post('/logout', ['_token' => $token]);

    $this->assertGuest();
    $response->assertRedirect('/');
});
