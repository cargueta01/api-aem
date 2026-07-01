<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_enterprise_for_existing_company(): void
    {
        $this->actingAs(User::factory()->create(), 'api');
        $company = Company::query()->create([
            'name' => 'Grupo AEM',
            'companys_status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/enterprises', [
            'company_id' => $company->id,
            'name' => 'AEM Express',
        ]);

        $response->assertCreated()
            ->assertJsonPath('company_id', $company->id)
            ->assertJsonPath('name', 'AEM Express')
            ->assertJsonPath('enterprises_status', 'active');
    }

    public function test_enterprise_creation_rejects_non_existing_parent_company(): void
    {
        $this->actingAs(User::factory()->create(), 'api');

        $response = $this->postJson('/api/v1/enterprises', [
            'company_id' => 999,
            'name' => 'AEM Express',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id']);
    }

    public function test_delete_deactivates_enterprise_safely(): void
    {
        $this->actingAs(User::factory()->create(), 'api');
        $company = Company::query()->create([
            'name' => 'Grupo AEM',
            'companys_status' => 'active',
        ]);
        $enterprise = Enterprise::query()->create([
            'company_id' => $company->id,
            'name' => 'AEM Express',
            'enterprises_status' => 'active',
        ]);

        $response = $this->deleteJson("/api/v1/enterprises/{$enterprise->id}");

        $response->assertOk()
            ->assertJsonPath('data.enterprises_status', 'inactive');
    }
}
