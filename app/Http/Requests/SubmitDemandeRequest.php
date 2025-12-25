<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDemandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $demande = $this->route('demande');
        return $this->user()->can('submit', $demande);
    }

    public function rules(): array
    {
        return [
            // Validation de complétude (à enrichir selon règles métier)
        ];
    }

    public function messages(): array
    {
        return [
            // Messages personnalisés
        ];
    }
}
