<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\Contracts\BranchRepositoryInterface;
use App\Repositories\Contracts\EnterpriseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BranchService
{
    public function __construct(
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly EnterpriseRepositoryInterface $enterpriseRepository
    ) {}

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->branchRepository->paginate($filters, $perPage);
    }

    public function findById(int $id): Branch
    {
        $branch = $this->branchRepository->findById($id);

        if (! $branch) {
            throw new NotFoundHttpException('Branch not found.');
        }

        return $branch;
    }

    public function create(array $data): Branch
    {
        $this->ensureEnterpriseExists((int) $data['enterprise_id']);

        $data['branchs_status'] = $data['branchs_status'] ?? 'active';

        return $this->branchRepository->create($data);
    }

    public function update(int $id, array $data): Branch
    {
        $branch = $this->findById($id);

        if (isset($data['enterprise_id'])) {
            $this->ensureEnterpriseExists((int) $data['enterprise_id']);
        }

        return $this->branchRepository->update($branch, $data);
    }

    public function deactivate(int $id): Branch
    {
        $branch = $this->findById($id);

        return $this->branchRepository->update($branch, [
            'branchs_status' => 'inactive',
        ]);
    }

    private function ensureEnterpriseExists(int $enterpriseId): void
    {
        if (! $this->enterpriseRepository->findById($enterpriseId)) {
            throw ValidationException::withMessages([
                'enterprise_id' => ['The selected enterprise does not exist.'],
            ]);
        }
    }
}