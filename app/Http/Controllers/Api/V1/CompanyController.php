<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Company\CreateCompanyDto;
use App\DTOs\Company\UpdateCompanyDto;
use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        private readonly CompanyService $companyService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companies = $this->companyService->paginate(
            (int) $request->query('per_page', 15)
        );

        return response()->json($companies);
    }

    public function store(Request $request): JsonResponse
    {
        $dto = CreateCompanyDto::fromRequest($request);
        $company = $this->companyService->create($dto->toArray());

        return response()->json($company, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->companyService->findById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dto = UpdateCompanyDto::fromRequest($request);
        $company = $this->companyService->update($id, $dto->toArray());

        return response()->json($company);
    }

    public function destroy(int $id): JsonResponse
    {
        $company = $this->companyService->deactivate($id);

        return response()->json([
            'message' => 'Company deactivated successfully.',
            'data' => $company,
        ]);
    }
}
