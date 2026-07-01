<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Enterprise\CreateEnterpriseDto;
use App\DTOs\Enterprise\UpdateEnterpriseDto;
use App\Http\Controllers\Controller;
use App\Services\EnterpriseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    public function __construct(
        private readonly EnterpriseService $enterpriseService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $enterprises = $this->enterpriseService->paginate(
            (int) $request->query('per_page', 15)
        );

        return response()->json($enterprises);
    }

    public function store(Request $request): JsonResponse
    {
        $dto = CreateEnterpriseDto::fromRequest($request);
        $enterprise = $this->enterpriseService->create($dto->toArray());

        return response()->json($enterprise, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->enterpriseService->findById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dto = UpdateEnterpriseDto::fromRequest($request);
        $enterprise = $this->enterpriseService->update($id, $dto->toArray());

        return response()->json($enterprise);
    }

    public function destroy(int $id): JsonResponse
    {
        $enterprise = $this->enterpriseService->deactivate($id);

        return response()->json([
            'message' => 'Enterprise deactivated successfully.',
            'data' => $enterprise,
        ]);
    }
}
