<?php

namespace App\Services;

use App\Models\Enterprise;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\EnterpriseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnterpriseService
{
    public function __construct(
        private readonly EnterpriseRepositoryInterface $enterpriseRepository,
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->enterpriseRepository->paginate($perPage);
    }

    public function findById(int $id): Enterprise
    {
        $enterprise = $this->enterpriseRepository->findById($id);

        if (! $enterprise) {
            throw new NotFoundHttpException('Enterprise not found.');
        }

        return $enterprise;
    }

    public function create(array $data): Enterprise
    {
        $this->ensureCompanyExists((int) $data['company_id']);

        $data['enterprises_status'] = $data['enterprises_status'] ?? 'active';

        return $this->enterpriseRepository->create($data);
    }

    public function update(int $id, array $data): Enterprise
    {
        $enterprise = $this->findById($id);

        if (isset($data['company_id'])) {
            $this->ensureCompanyExists((int) $data['company_id']);
        }

        return $this->enterpriseRepository->update($enterprise, $data);
    }

    public function deactivate(int $id): Enterprise
    {
        $enterprise = $this->findById($id);

        return $this->enterpriseRepository->update($enterprise, [
            'enterprises_status' => 'inactive',
        ]);
    }

    private function ensureCompanyExists(int $companyId): void
    {
        if (! $this->companyRepository->findById($companyId)) {
            throw ValidationException::withMessages([
                'company_id' => ['The selected company does not exist.'],
            ]);
        }
    }
}