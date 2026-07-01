<?php

namespace App\DTOs\Company;

use Illuminate\Http\Request;

class UpdateCompanyDto
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $companysStatus = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'companys_status' => ['sometimes', 'string', 'in:active,inactive'],
        ]);

        return new self(
            name: $data['name'] ?? null,
            companysStatus: $data['companys_status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'companys_status' => $this->companysStatus,
        ], fn ($value) => $value !== null);
    }
}