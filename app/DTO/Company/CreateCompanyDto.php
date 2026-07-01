<?php

namespace App\DTOs\Company;

class CreateCompanyDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $status = 'active'
    ) {}

    /**
     * Permite construir el DTO de forma limpia desde un array (como el Request validado)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            status: $data['companys_status'] ?? 'active'
        );
    }

    /**
     * Convierte el DTO a array si el Repositorio de Eloquent lo necesita
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'companys_status' => $this->status,
        ];
    }
}