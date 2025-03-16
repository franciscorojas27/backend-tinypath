<?php

use App\Models\User;

test('user without token cannot get short links', function () {
    $response = $this->getJson('/api/shortLinks');

    $response->assertUnauthorized()->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});
test('user can get their short links', function () {
    $user = User::factory()->hasShortLinks(3)->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/shortLinks');

    $response->assertOk()->assertJsonFragment([
        'id' => $user->shortLinks->first()->id,
        'short_link' => $user->shortLinks->first()->short_link,
        'original_link' => $user->shortLinks->first()->original_link,
    ]);
});
test('user may not have short links', function () {
    $user = User::factory()->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/shortLinks');

    $response->assertNotFound()->assertJson(['error' => 'No links found for the user.']);
});
test('user no logged cannot create shorts links', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/shortLinks');

    $response->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
});
test('user can create a short link', function () {
    $user = User::factory()->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/shortLinks', [
        'original_link' => 'https://google.com'
    ]);

    $response->assertCreated()->assertJson(['message' => 'Link created successfully.']);
});
test('user cannot create a short link with invalid data', function () {
    $user = User::factory()->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/shortLinks', [
        'original_link' => 'buenas tardes',
    ]);
    $response->assertUnprocessable()->assertJsonValidationErrors([
        'original_link' => 'The original link field must be a valid URL.',
    ]);
});
test('user can update their short link', function () {
    $user = User::factory()->hasShortLinks(1)->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->putJson('/api/shortLinks/' . $user->shortLinks->first()->short_link, [
        'original_link' => 'https://google.com',
    ]);
    $response->assertOk()->assertJson(['message' => 'Link updated successfully.']);

    $this->assertDatabaseHas('short_links', [
        'short_link' => $user->shortLinks->first()->short_link,
        'original_link' => 'https://google.com',
    ]);
});
test('user can only update their own short links, not others', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->hasShortLinks(1)->create();

    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->putJson('/api/shortLinks/' . $otherUser->shortLinks->first()->short_link, [
        'original_link' => 'https://google.com',
    ]);
    $response->assertUnauthorized()->assertJson(['error' => 'You do not have permission to update this link.']);
});
test('user cannot update short link with invalid data', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->hasShortLinks(1)->create();

    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->putJson('/api/shortLinks/' . $otherUser->shortLinks->first()->short_link, [
        'original_link' => 'buenas tardes',
    ]);
    $response->assertUnprocessable()->assertJsonValidationErrors([
        'original_link' => 'The original link field must be a valid URL.',
    ]);
});
test('user without auth cannot update short link', function () {
    $otherUser = User::factory()->hasShortLinks(1)->create();

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->putJson('/api/shortLinks/' . $otherUser->shortLinks->first()->short_link, [
        'original_link' => 'buenas tardes',
    ]);
    $response->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
});

test('user can delete their short link', function () {
    $user = User::factory()->hasShortLinks(1)->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->deleteJson('/api/shortLinks/' . $user->shortLinks->first()->short_link);
    $response->assertOk()->assertJson(['message' => 'Link deleted successfully.']);
});
test('user can only delete their own short links, not others', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->hasShortLinks(1)->create();

    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->deleteJson('/api/shortLinks/' . $otherUser->shortLinks->first()->short_link);
    $response->assertUnauthorized()->assertJson(['error' => 'You do not have permission to delete this link.']);
});
test('user cannot delete short link with invalid short link', function () {
    $user = User::factory()->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->deleteJson('/api/shortLinks/');
    $response->assertMethodNotAllowed();
});
test('user without auth cannot delete short link', function () {
    $otherUser = User::factory()->hasShortLinks(1)->create();
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->deleteJson('/api/shortLinks/' . $otherUser->shortLinks->first()->short_link);
    $response->assertJsonFragment(['message' => 'Unauthenticated.']);
});
test('QR generated successfully', function () {
    $user = User::factory()->hasShortLinks(1)->create();
    $token = $user->createToken('App')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/shortLinks/' . $user->shortLinks->first()->short_link . '/qr');
    $response->assertOk()->assertHeader('Content-Type', 'image/png');
});
test('user cannot generate QR of another user short link', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->hasShortLinks(1)->create();
    $token = $user->createToken('App')->plainTextToken;

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/shortLinks/' . $otherUser->shortLinks->first()->short_link . '/qr');
    $response->assertUnauthorized()->assertJson(['error' => 'You do not have permission to generate QR of this link.']);
});
