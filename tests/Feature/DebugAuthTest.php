<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('auth attempt works in test environment', function () {
    $user = User::factory()->create(['email' => 'debug@example.com', 'password' => bcrypt('password')]);

    $attempt = Auth::attempt(['email' => 'debug@example.com', 'password' => 'password']);

    expect($attempt)->toBeTrue();
});
