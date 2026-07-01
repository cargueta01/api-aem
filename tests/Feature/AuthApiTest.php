<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_jwt_token(): void
    {
        User::factory()->create([
            'email' => 'admin@aem.test',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@aem.test',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_protected_endpoint_rejects_unauthenticated_requests(): void
    {
        $response = $this->getJson('/api/v1/companys');

        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
                'errors' => [],
            ]);
    }
}
