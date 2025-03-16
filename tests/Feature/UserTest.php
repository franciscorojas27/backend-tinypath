<?php

use App\Models\User as AppUser;
use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\DB;

beforeEach(function () {
    artisan('migrate:refresh --seed');
});

test('can see user values', function () {
    $user = AppUser::factory()->create();
    $user->createToken('App');

    $response = $this->actingAs($user)->get('/api/users/profile');

    $response->assertStatus(200)->assertJson([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ]);
});

test('user no unauthorized cant see values', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->getJson('/api/users/profile');
    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

test('user can update values', function () {
    $user = AppUser::factory()->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->putJson('/api/users/profile', [
        'name' => 'pep',
        'email' => 'franco1@example.com',
    ]);
    $response->assertStatus(200)
        ->assertJson(['message' => 'User updated successfully.']);

    $this->assertDatabaseHas('users', [
        'name' => 'pep',
        'email' => 'franco1@example.com',
    ]);
});

test('user cannot update his account with an invalid token', function () {
    $user = AppUser::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . 'invalidToken',
    ])->putJson('/api/users/profile', [
        'name' => 'pep',
        'email' => 'franco1@example.com',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});


test('user can delete their account', function () {
    $user = AppUser::factory()->create([
        'password' => bcrypt('password'),
    ]);
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->deleteJson('/api/users/profile', [
        'password' => 'password',
    ]);
    $response->assertStatus(200)
        ->assertJson(['message' => 'User deleted successfully.']);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

test('user cannot delete his account with an invalid token', function () {
    $user = AppUser::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . 'invalidToken',
    ])->deleteJson('/api/users/profile', [
        'password' => 'password',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});


test('user cant delete him account with a wrong password', function () {
    $user = AppUser::factory()->create([
        'password' => bcrypt('password'),
    ]);
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->deleteJson('/api/users/profile', [
        'password' => '281090093',
    ]);
    $response->assertStatus(401)
        ->assertJson(['error' => 'Incorrect password.']);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});
