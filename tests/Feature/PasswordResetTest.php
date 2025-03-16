<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;

test('can send email to reset password', function () {
    Notification::fake();
    $user = User::factory()->create();
    $response = $this->post('/api/password/forgot-password', ['email' => $user->email]);
    $response->assertStatus(200)
        ->assertJson(['message' => 'Password reset link sent to your email address.']);
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('cant send email to reset password', function () {
    Mail::fake();
    $user = User::factory()->create();

    Password::shouldReceive('sendResetLink')
        ->once()
        ->with(['email' => $user->email])
        ->andReturn(Password::INVALID_USER);

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/password/forgot-password', ['email' => $user->email]);

    $response->assertStatus(500)
        ->assertJsonFragment(['error' => 'Error sending password reset link.']);

    Mail::assertNothingSent();
});

test('can reset password with token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $response = $this->post('/api/password/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password1',
        'password_confirmation' => 'password1'
    ]);
    $response->assertStatus(200)
        ->assertJson(['message' => 'Password reset successfully.']);

    $this->assertTrue(Hash::check('password1', $user->fresh()->password));
});

test('cant rest password with invalid token', function () {
    $user = User::factory()->create();
    $response = $this->post('/api/password/reset-password', [
        'token' => 'j123jh12j3h-98as09du12l3',
        'email' => $user->email,
        'password' => 'password1',
        'password_confirmation' => 'password1'
    ]);
    $response->assertStatus(400)
        ->assertJson(['error' => 'Invalid or expired token.']);
});
