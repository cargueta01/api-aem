<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enterprise_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'doc_number' => ['required', 'string', 'max:50'],
            'municipality_codigo' => ['required', 'string', 'max:50'],
            'branchs_status' => ['sometimes', 'string', 'in:active,inactive'],
        ];
    }
}