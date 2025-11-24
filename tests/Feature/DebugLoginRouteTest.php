<?php

use App\Models\User;

test('post to login route using email', function () {
    $user = User::factory()->create(['email' => 'debugpost@example.com', 'password' => bcrypt('password')]);
    $this->get('/login');
    $token = session('_token');

    $response = $this->post('/login', [
        '_token' => $token,
        'email' => 'debugpost@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
});
