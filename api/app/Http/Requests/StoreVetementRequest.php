<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Contrôle les données envoyées lors de l'ajout d'un vêtement.. Si un vêtement ne respecte pas une règle, on evoie une réponse 422 (liste des erreurs)
 */
class StoreVetementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // La catégorie doit exister en base.
            'categorie_id' => ['required', 'integer', 'exists:categories,id'],

            'nom' => ['required', 'string', 'max:100'],

            // Format hexadécimal strict
            'couleur_hex' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],

            'motif' => ['nullable', 'string', 'max:50'],

            // Plage de température en degrés Celsius.
            'temp_min' => ['required', 'integer', 'between:-40,50'],
            'temp_max' => ['required', 'integer', 'between:-40,50', 'gte:temp_min'],

            'impermeable' => ['boolean'],
            'coupe_vent'  => ['boolean'],

            // 'mimetypes' vérifie le type du fichier, pas son extension.
            'photo' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,image/heic,image/heif',
                'max:8192',
            ],
        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'categorie_id.exists'   => 'Cette catégorie n\'existe pas.',
            'nom.required'          => 'Le nom du vêtement est obligatoire.',
            'couleur_hex.regex'     => 'La couleur doit être au format hexadécimal, par exemple #3F334D.',
            'temp_max.gte'          => 'La température maximale doit être supérieure ou égale à la minimale.',
            'photo.required'        => 'Une photo du vêtement est obligatoire.',
            'photo.mimetypes'       => 'Le fichier envoyé n\'est pas une image valide.',
            'photo.max'             => 'La photo ne doit pas dépasser 8 Mo.',
        ];
    }
}
