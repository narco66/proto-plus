<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAyantDroitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ayantDroitParam = $this->route('ayant_droit') ?? $this->route('ayants_droit') ?? $this->route('ayantDroit');
        $ayantDroit = $ayantDroitParam instanceof \App\Models\AyantDroit
            ? $ayantDroitParam
            : \App\Models\AyantDroit::find($ayantDroitParam);

        if (!$ayantDroit) {
            return false;
        }

        return $ayantDroit && $this->user()->id === $ayantDroit->fonctionnaire_user_id;
    }

    public function rules(): array
    {
        return [
            'civilite' => ['sometimes', 'in:M.,Mme,Mlle'],
            'nom' => ['sometimes', 'string', 'max:255'],
            'prenom' => ['sometimes', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'lieu_naissance' => ['nullable', 'string', 'max:255'],
            'lien_familial' => ['sometimes', 'in:conjoint,enfant,autre'],
            'nationalite' => ['nullable', 'string', 'max:100'],
            'passeport_num' => ['nullable', 'string', 'max:50'],
            'passeport_expire_at' => ['nullable', 'date', 'after:today'],
        ];
    }
}
