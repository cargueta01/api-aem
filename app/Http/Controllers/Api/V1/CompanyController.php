<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
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

    public function store(CompanyStoreRequest $request): JsonResponse
    {
        $company = $this->companyService->create($request->validated());

        return response()->json($company, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->companyService->findById($id));
    }

    public function update(CompanyUpdateRequest $request, int $id): JsonResponse
    {
        $company = $this->companyService->update($id, $request->validated());

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