<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_company(): void
    {
        $this->actingAs(User::factory()->create(), 'api');

        $response = $this->postJson('/api/v1/companys', [
            'name' => 'Grupo AEM',
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Grupo AEM')
            ->assertJsonPath('companys_status', 'active');

        $this->assertDatabaseHas('companys', [
            'name' => 'Grupo AEM',
            'companys_status' => 'active',
        ]);
    }

    public function test_company_creation_rejects_incomplete_payload(): void
    {
        $this->actingAs(User::factory()->create(), 'api');

        $response = $this->postJson('/api/v1/companys', []);

        $response->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed.')
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_deactivates_company_safely(): void
    {
        $this->actingAs(User::factory()->create(), 'api');
        $company = Company::query()->create([
            'name' => 'Grupo AEM',
            'companys_status' => 'active',
        ]);

        $response = $this->deleteJson("/api/v1/companys/{$company->id}");

        $response->assertOk()
            ->assertJsonPath('data.companys_status', 'inactive');

        $this->assertDatabaseHas('companys', [
            'id' => $company->id,
            'companys_status' => 'inactive',
        ]);
    }
}
