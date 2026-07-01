<?php

namespace App\DTOs\Enterprise;

use Illuminate\Http\Request;

class CreateEnterpriseDto
{
    public function __construct(
        public readonly int $companyId,
        public readonly string $name,
        public readonly string $enterprisesStatus = 'active',
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'enterprises_status' => ['sometimes', 'string', 'in:active,inactive'],
        ]);

        return new self(
            companyId: (int) $data['company_id'],
            name: $data['name'],
            enterprisesStatus: $data['enterprises_status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'company_id' => $this->companyId,
            'name' => $this->name,
            'enterprises_status' => $this->enterprisesStatus,
        ];
    }
}