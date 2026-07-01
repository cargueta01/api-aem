<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['sometimes', 'required', 'integer'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'enterprises_status' => ['sometimes', 'string', 'in:active,inactive'],
        ];
    }
}