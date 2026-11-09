<?php

namespace App\Http\Requests;

use App\Models\Plat;
use Illuminate\Foundation\Http\FormRequest;

class StorePlatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Plat::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ];
    }
}
