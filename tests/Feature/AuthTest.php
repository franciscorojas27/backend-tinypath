
<?php

use App\Models\User;

test('can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password')
    ]);
    $response = $this->postJson('/api/login', [
        'email' =>  $user->email,
        'password' => 'password'
    ]);
    $response->assertJsonStructure(['token']);
});
test('cant login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password')
    ]);
    $response = $this->postJson('/api/login', [
        'email' => $user->email,       'password' => 'wrong-password',
    ]);
    $response->assertStatus(401);
});

test('can register new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);
    $response->assertCreated();
});

test('cant register user with invalid data', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'pepe1',
        'email' => 'franco3@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'wrong-password'
    ]);

    $response->assertUnprocessable()
        ->assertInvalid('password');
});

test('can logout user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/logout');
    $response->assertStatus(200)
        ->assertJson(['message' => 'Logout successful']);
});

test('unauthenticated user cant logout', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post('/api/logout');
    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

