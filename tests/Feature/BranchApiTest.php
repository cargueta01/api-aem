<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_branch_for_existing_enterprise(): void
    {
        $this->actingAs(User::factory()->create(), 'api');
        $enterprise = $this->createEnterprise();

        $response = $this->postJson('/api/v1/branchs', [
            'enterprise_id' => $enterprise->id,
            'name' => 'Sucursal Centro',
            'doc_number' => 'BR-0001',
            'municipality_codigo' => '0601',
        ]);

        $response->assertCreated()
            ->assertJsonPath('enterprise_id', $enterprise->id)
            ->assertJsonPath('municipality_codigo', '0601')
            ->assertJsonPath('branchs_status', 'active');
    }

    public function test_branch_creation_rejects_non_existing_parent_enterprise(): void
    {
        $this->actingAs(User::factory()->create(), 'api');

        $response = $this->postJson('/api/v1/branchs', [
            'enterprise_id' => 999,
            'name' => 'Sucursal Centro',
            'doc_number' => 'BR-0001',
            'municipality_codigo' => '0601',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['enterprise_id']);
    }

    public function test_branch_list_can_be_filtered_by_enterprise_and_municipality(): void
    {
        $this->actingAs(User::factory()->create(), 'api');
        $enterprise = $this->createEnterprise();
        $anotherEnterprise = $this->createEnterprise('Otra Empresa');

        Branch::query()->create([
            'enterprise_id' => $enterprise->id,
            'name' => 'Sucursal Centro',
            'doc_number' => 'BR-0001',
            'municipality_codigo' => '0601',
            'branchs_status' => 'active',
        ]);
        Branch::query()->create([
            'enterprise_id' => $enterprise->id,
            'name' => 'Sucursal Norte',
            'doc_number' => 'BR-0002',
            'municipality_codigo' => '0602',
            'branchs_status' => 'active',
        ]);
        Branch::query()->create([
            'enterprise_id' => $anotherEnterprise->id,
            'name' => 'Sucursal Sur',
            'doc_number' => 'BR-0003',
            'municipality_codigo' => '0601',
            'branchs_status' => 'active',
        ]);

        $response = $this->getJson("/api/v1/branchs?enterprise_id={$enterprise->id}&municipality_codigo=0601");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Sucursal Centro');
    }

    private function createEnterprise(string $enterpriseName = 'AEM Express'): Enterprise
    {
        $company = Company::query()->create([
            'name' => 'Grupo '.$enterpriseName,
            'companys_status' => 'active',
        ]);

        return Enterprise::query()->create([
            'company_id' => $company->id,
            'name' => $enterpriseName,
            'enterprises_status' => 'active',
        ]);
    }
}
