<?php

namespace App\DTOs\Branch;

use Illuminate\Http\Request;

class CreateBranchDto
{
    public function __construct(
        public readonly int $enterpriseId,
        public readonly string $name,
        public readonly string $docNumber,
        public readonly string $municipalityCodigo,
        public readonly string $branchsStatus = 'active',
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'enterprise_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'doc_number' => ['required', 'string', 'max:50'],
            'municipality_codigo' => ['required', 'string', 'max:50'],
            'branchs_status' => ['sometimes', 'string', 'in:active,inactive'],
        ]);

        return new self(
            enterpriseId: (int) $data['enterprise_id'],
            name: $data['name'],
            docNumber: $data['doc_number'],
            municipalityCodigo: $data['municipality_codigo'],
            branchsStatus: $data['branchs_status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'enterprise_id' => $this->enterpriseId,
            'name' => $this->name,
            'doc_number' => $this->docNumber,
            'municipality_codigo' => $this->municipalityCodigo,
            'branchs_status' => $this->branchsStatus,
        ];
    }
}
