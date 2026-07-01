<?php

namespace App\Repositories\Eloquent;

use App\Models\Branch;
use App\Repositories\Contracts\BranchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BranchRepository implements BranchRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Branch::query()
            ->with('enterprise')
            ->when($filters['enterprise_id'] ?? null, function ($query, $enterpriseId) {
                $query->where('enterprise_id', $enterpriseId);
            })
            ->when($filters['municipality_codigo'] ?? null, function ($query, $municipalityCodigo) {
                $query->where('municipality_codigo', $municipalityCodigo);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Branch
    {
        return Branch::query()
            ->with('enterprise')
            ->find($id);
    }

    public function create(array $data): Branch
    {
        return Branch::query()->create($data);
    }

    public function update(Branch $branch, array $data): Branch
    {
        $branch->update($data);

        return $branch->refresh();
    }

    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }
}