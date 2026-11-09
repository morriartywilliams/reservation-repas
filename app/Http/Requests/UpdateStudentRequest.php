<?php

namespace App\Http\Requests;

use App\Models\Eleve;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $eleve = $this->route('eleve');
        return $this->user()?->can('update', $eleve) ?? false;
    }

    public function rules(): array
    {
        $eleve = $this->route('eleve');
        $eleveId = $eleve instanceof Eleve ? $eleve->id : $eleve;
        $userId = $eleve instanceof Eleve ? $eleve->user_id : Eleve::find($eleveId)?->user_id;

        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'sometimes', 'string', 'min:8', 'confirmed'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'matricule' => [
                'required',
                'string',
                'max:30',
                Rule::unique('eleves', 'matricule')->ignore($eleveId),
            ],
            'classe' => ['required', 'string', 'max:50'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
        ];
    }
}
