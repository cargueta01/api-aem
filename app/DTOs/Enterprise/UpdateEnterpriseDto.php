<?php

namespace App\DTOs\Enterprise;

use Illuminate\Http\Request;

class UpdateEnterpriseDto
{
    public function __construct(
        public readonly ?int $companyId = null,
        public readonly ?string $name = null,
        public readonly ?string $enterprisesStatus = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'company_id' => ['sometimes', 'required', 'integer'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'enterprises_status' => ['sometimes', 'string', 'in:active,inactive'],
        ]);

        return new self(
            companyId: isset($data['company_id']) ? (int) $data['company_id'] : null,
            name: $data['name'] ?? null,
            enterprisesStatus: $data['enterprises_status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'company_id' => $this->companyId,
            'name' => $this->name,
            'enterprises_status' => $this->enterprisesStatus,
        ], fn ($value) => $value !== null);
    }
}