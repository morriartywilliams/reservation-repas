<?php

namespace App\Http\Requests;

use App\Models\Paiement;
use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Paiement::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'reservation_id' => ['required', 'exists:reservations,id', 'unique:paiements,reservation_id'],
            'montant' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', 'in:paye,impaye,exonere'],
            'moyen' => ['nullable', 'required_if:statut,paye', 'in:especes,carte_cantine'],
            'date' => ['nullable', 'date'],
        ];
    }
}
