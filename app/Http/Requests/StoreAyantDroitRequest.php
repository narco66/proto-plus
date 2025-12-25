<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAyantDroitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('ayants_droit.create');
    }

    public function rules(): array
    {
        return [
            'civilite' => ['required', 'in:M.,Mme,Mlle'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'lieu_naissance' => ['nullable', 'string', 'max:255'],
            'lien_familial' => ['required', 'in:conjoint,enfant,autre'],
            'nationalite' => ['nullable', 'string', 'max:100'],
            'passeport_num' => ['nullable', 'string', 'max:50'],
            'passeport_expire_at' => ['nullable', 'date', 'after:today'],
        ];
    }
}
