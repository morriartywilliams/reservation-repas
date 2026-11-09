<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatRequest extends FormRequest
{
    public function authorize(): bool
    {
        $plat = $this->route('plat');
        return $this->user()?->can('update', $plat) ?? false;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ];
    }
}
