<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_key' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9][-a-zA-Z0-9.]*[a-zA-Z0-9]$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'license_key.required' => 'License key is required.',
            'domain.required' => 'Domain is required.',
            'domain.regex' => 'Domain format is invalid.',
        ];
    }
}
