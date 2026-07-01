<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyService
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->companyRepository->paginate($perPage);
    }

    public function findById(int $id): Company
    {
        $company = $this->companyRepository->findById($id);

        if (! $company) {
            throw new NotFoundHttpException('Company not found.');
        }

        return $company;
    }

    public function create(array $data): Company
    {
        $data['companys_status'] = $data['companys_status'] ?? 'active';

        return $this->companyRepository->create($data);
    }

    public function update(int $id, array $data): Company
    {
        $company = $this->findById($id);

        return $this->companyRepository->update($company, $data);
    }

    public function deactivate(int $id): Company
    {
        $company = $this->findById($id);

        return $this->companyRepository->update($company, [
            'companys_status' => 'inactive',
        ]);
    }
}