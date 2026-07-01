<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Branch\BranchFiltersDto;
use App\DTOs\Branch\CreateBranchDto;
use App\DTOs\Branch\UpdateBranchDto;
use App\Http\Controllers\Controller;
use App\Services\BranchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(
        private readonly BranchService $branchService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $dto = BranchFiltersDto::fromRequest($request);

        $branches = $this->branchService->paginate(
            $dto->filters(),
            $dto->perPage
        );

        return response()->json($branches);
    }

    public function store(Request $request): JsonResponse
    {
        $dto = CreateBranchDto::fromRequest($request);
        $branch = $this->branchService->create($dto->toArray());

        return response()->json($branch, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->branchService->findById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dto = UpdateBranchDto::fromRequest($request);
        $branch = $this->branchService->update($id, $dto->toArray());

        return response()->json($branch);
    }

    public function destroy(int $id): JsonResponse
    {
        $branch = $this->branchService->deactivate($id);

        return response()->json([
            'message' => 'Branch deactivated successfully.',
            'data' => $branch,
        ]);
    }
}
