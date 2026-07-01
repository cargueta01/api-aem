<?php

namespace App\DTOs\Company;

class CreateCompanyDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $status = 'active'
    ) {}

    /**
     *  DTO de  desde un array 
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            status: $data['companys_status'] ?? 'active'
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'companys_status' => $this->status,
        ];
    }
}