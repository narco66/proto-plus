<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDemandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('demande'));
    }

    public function rules(): array
    {
        return [
            'type_demande' => [
                'sometimes',
                'in:visa_diplomatique,visa_courtoisie,visa_familial,carte_diplomatique,carte_consulaire,franchise_douaniere,immatriculation_diplomatique,autorisation_entree,autorisation_sortie',
            ],
            'motif' => ['sometimes', 'string'],
            'date_depart_prevue' => ['sometimes', 'date'],
            'pays_destination' => ['sometimes', 'string', 'max:255'],
            'priorite' => ['nullable', 'in:normal,urgent'],
            'beneficiaires' => ['sometimes', 'array', 'min:1'],
            'beneficiaires.*.beneficiaire_type' => ['required_with:beneficiaires', 'in:fonctionnaire,ayant_droit'],
            'beneficiaires.*.beneficiaire_id' => ['required_with:beneficiaires', 'integer'],
            'beneficiaires.*.role_dans_demande' => ['nullable', 'in:principal,secondaire'],
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*.type_document' => ['required_with:documents.*.file', 'in:passeport,carte_identite,acte_naissance,justificatif_domicile,photo_identite,autre'],
            'documents.*.titre' => ['required_with:documents.*.file', 'string', 'max:255'],
            'documents.*.description' => ['required_with:documents.*.file', 'string'],
            'documents.*.file' => ['required_with:documents.*.titre', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'documents.*.beneficiaire_type' => ['nullable', 'in:fonctionnaire,ayant_droit'],
            'documents.*.beneficiaire_id' => ['nullable', 'integer'],
        ];
    }
}
