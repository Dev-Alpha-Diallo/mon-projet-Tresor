<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'etudiant_id' => 'required|exists:etudiants,id',
            'montant' => 'required|numeric|min:0.01|max:999999999.99',
            'date_paiement' => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:' . implode(',', array_keys(config('tresorerie.payment_methods'))),
            'remarque' => 'nullable|string|max:500',
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'etudiant_id.required' => 'L\'étudiant est obligatoire.',
            'etudiant_id.exists' => 'L\'étudiant sélectionné n\'existe pas.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'date_paiement.required' => 'La date de paiement est obligatoire.',
            'date_paiement.date' => 'La date de paiement n\'est pas valide.',
            'date_paiement.before_or_equal' => 'La date de paiement ne doit pas être dans le futur.',
            'moyen_paiement.required' => 'Le moyen de paiement est obligatoire.',
            'moyen_paiement.in' => 'Le moyen de paiement n\'est pas valide.',
            'remarque.max' => 'La remarque ne doit pas dépasser 500 caractères.',
        ];
    }

    /**
     * Validations personnalisées supplémentaires
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Récupérer l'étudiant si l'ID existe
            if ($this->has('etudiant_id')) {
                $etudiant = \App\Models\Etudiant::find($this->etudiant_id);
                
                if ($etudiant && $this->date_paiement < $etudiant->created_at->format('Y-m-d')) {
                    $validator->errors()->add('date_paiement', 
                        'La date de paiement ne peut pas être antérieure à l\'inscription de l\'étudiant.');
                }
            }
        });
    }
}
