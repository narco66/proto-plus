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
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*.type_document' => ['required_with:documents.*.file', 'in:passeport,carte_identite,acte_naissance,justificatif_domicile,photo_identite,autre'],
            'documents.*.titre' => ['required_with:documents.*.file', 'string', 'max:255'],
            'documents.*.description' => ['required_with:documents.*.file', 'string'],
            'documents.*.file' => ['required_with:documents.*.titre', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'documents.*.beneficiaire_type' => ['nullable', 'in:fonctionnaire,ayant_droit'],
            'documents.*.beneficiaire_id' => ['nullable', 'integer'],
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
            'documents.*.titre.required_with' => 'Un titre est requis pour chaque pièce jointe.',
            'documents.*.description.required_with' => 'Une description est requise pour chaque pièce jointe.',
            'documents.*.file.required_with' => 'Un fichier est requis pour chaque pièce jointe.',
            'documents.*.file.mimes' => 'Les pièces jointes doivent être au format PDF, JPG ou PNG.',
            'documents.*.file.max' => 'Les pièces jointes ne peuvent pas dépasser 10 MB.',
        ];
    }
}
