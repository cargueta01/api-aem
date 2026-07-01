<?php

namespace App\Repositories\Contracts;

use App\Models\Enterprise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EnterpriseRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Enterprise;

    public function create(array $data): Enterprise;

    public function update(Enterprise $enterprise, array $data): Enterprise;

    public function delete(Enterprise $enterprise): bool;
}