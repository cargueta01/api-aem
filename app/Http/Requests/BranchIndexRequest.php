<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enterprise_id' => ['sometimes', 'integer'],
            'municipality_codigo' => ['sometimes', 'string', 'max:50'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}