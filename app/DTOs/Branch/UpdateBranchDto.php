<?php

namespace App\DTOs\Branch;

use Illuminate\Http\Request;

class UpdateBranchDto
{
    public function __construct(
        public readonly ?int $enterpriseId = null,
        public readonly ?string $name = null,
        public readonly ?string $docNumber = null,
        public readonly ?string $municipalityCodigo = null,
        public readonly ?string $branchsStatus = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'enterprise_id' => ['sometimes', 'required', 'integer'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'doc_number' => ['sometimes', 'required', 'string', 'max:50'],
            'municipality_codigo' => ['sometimes', 'required', 'string', 'max:50'],
            'branchs_status' => ['sometimes', 'string', 'in:active,inactive'],
        ]);

        return new self(
            enterpriseId: isset($data['enterprise_id']) ? (int) $data['enterprise_id'] : null,
            name: $data['name'] ?? null,
            docNumber: $data['doc_number'] ?? null,
            municipalityCodigo: $data['municipality_codigo'] ?? null,
            branchsStatus: $data['branchs_status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'enterprise_id' => $this->enterpriseId,
            'name' => $this->name,
            'doc_number' => $this->docNumber,
            'municipality_codigo' => $this->municipalityCodigo,
            'branchs_status' => $this->branchsStatus,
        ], fn ($value) => $value !== null);
    }
}
