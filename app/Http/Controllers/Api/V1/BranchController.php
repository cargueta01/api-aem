<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchIndexRequest;
use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Services\BranchService;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function __construct(
        private readonly BranchService $branchService
    ) {}

    public function index(BranchIndexRequest $request): JsonResponse
    {
        $filters = $request->safe()->only([
            'enterprise_id',
            'municipality_codigo',
        ]);

        $branches = $this->branchService->paginate(
            $filters,
            (int) $request->query('per_page', 15)
        );

        return response()->json($branches);
    }

    public function store(BranchStoreRequest $request): JsonResponse
    {
        $branch = $this->branchService->create($request->validated());

        return response()->json($branch, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->branchService->findById($id));
    }

    public function update(BranchUpdateRequest $request, int $id): JsonResponse
    {
        $branch = $this->branchService->update($id, $request->validated());

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