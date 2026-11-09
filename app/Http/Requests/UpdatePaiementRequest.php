<?php

namespace App\Http\Requests;

use App\Models\Paiement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $paiement = $this->route('paiement');
        return $this->user()?->can('update', $paiement) ?? false;
    }

    public function rules(): array
    {
        $paiement = $this->route('paiement');
        $paiementId = $paiement instanceof Paiement ? $paiement->id : $paiement;

        return [
            'reservation_id' => [
                'required',
                'exists:reservations,id',
                Rule::unique('paiements', 'reservation_id')->ignore($paiementId),
            ],
            'montant' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', 'in:paye,impaye,exonere'],
            'moyen' => ['nullable', 'required_if:statut,paye', 'in:especes,carte_cantine'],
            'date' => ['nullable', 'date'],
        ];
    }
}
