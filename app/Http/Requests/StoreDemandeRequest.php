<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('demandes.create');
    }

    public function rules(): array
    {
        return [
            'type_demande' => [
                'required',
                'in:visa_diplomatique,visa_courtoisie,visa_familial,carte_diplomatique,carte_consulaire,franchise_douaniere,immatriculation_diplomatique,autorisation_entree,autorisation_sortie',
            ],
            'motif' => ['required', 'string'],
            'date_depart_prevue' => ['required', 'date'],
            'pays_destination' => ['required', 'string', 'max:255'],
            'priorite' => ['nullable', 'in:normal,urgent'],
            'beneficiaires' => ['required', 'array', 'min:1'],
            'beneficiaires.*.beneficiaire_type' => ['required', 'in:fonctionnaire,ayant_droit'],
            'beneficiaires.*.beneficiaire_id' => ['required', 'integer'],
            'beneficiaires.*.role_dans_demande' => ['nullable', 'in:principal,secondaire'],
        ];
    }

    public function messages(): array
    {
        return [
            'type_demande.required' => 'Le type de demande est obligatoire.',
            'type_demande.in' => 'Le type de demande sélectionné n\'est pas valide.',
            'motif.required' => 'Le motif est obligatoire.',
            'date_depart_prevue.required' => 'La date de départ prévue est obligatoire.',
            'pays_destination.required' => 'Le pays de destination est obligatoire.',
            'beneficiaires.required' => 'Au moins un bénéficiaire est requis.',
            'beneficiaires.min' => 'Au moins un bénéficiaire est requis.',
            'beneficiaires.*.beneficiaire_type.required' => 'Le type de bénéficiaire est requis.',
            'beneficiaires.*.beneficiaire_id.required' => 'L\'identifiant du bénéficiaire est requis.',
        ];
    }
}
