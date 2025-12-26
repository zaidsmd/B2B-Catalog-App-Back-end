<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('user can register', function () {
    $response = $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['user', 'token']);

    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['user', 'token']);
});

test('user can get me', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson(route('auth.me'));

    $response->assertOk()
        ->assertJson(['user' => ['id' => $user->id]]);
});

test('user can verify token', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson(route('auth.verify'));

    $response->assertOk()
        ->assertJson(['message' => 'Token is valid.']);
});

test('user can refresh token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('API Token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson(route('auth.refresh'));

    $response->assertOk()
        ->assertJsonStructure(['user', 'token']);

    $newToken = $response->json('token');
    expect($newToken)->not->toBe($token);

    Auth::forgetGuards();

    // Old token should be revoked
    $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson(route('auth.me'))
        ->assertStatus(401);
});

test('user can logout', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson(route('auth.logout'));

    $response->assertOk();
});
