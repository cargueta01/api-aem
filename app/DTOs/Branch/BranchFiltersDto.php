<?php

namespace App\DTOs\Branch;

use Illuminate\Http\Request;

class BranchFiltersDto
{
    public function __construct(
        public readonly ?int $enterpriseId = null,
        public readonly ?string $municipalityCodigo = null,
        public readonly int $perPage = 15,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'enterprise_id' => ['sometimes', 'integer'],
            'municipality_codigo' => ['sometimes', 'string', 'max:50'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        return new self(
            enterpriseId: isset($data['enterprise_id']) ? (int) $data['enterprise_id'] : null,
            municipalityCodigo: $data['municipality_codigo'] ?? null,
            perPage: isset($data['per_page']) ? (int) $data['per_page'] : 15,
        );
    }

    public function filters(): array
    {
        return array_filter([
            'enterprise_id' => $this->enterpriseId,
            'municipality_codigo' => $this->municipalityCodigo,
        ], fn ($value) => $value !== null);
    }
}
