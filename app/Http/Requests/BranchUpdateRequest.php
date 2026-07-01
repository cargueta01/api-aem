<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enterprise_id' => ['sometimes', 'required', 'integer'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'doc_number' => ['sometimes', 'required', 'string', 'max:50'],
            'municipality_codigo' => ['sometimes', 'required', 'string', 'max:50'],
            'branchs_status' => ['sometimes', 'string', 'in:active,inactive'],
        ];
    }
}