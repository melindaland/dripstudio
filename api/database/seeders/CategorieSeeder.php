<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

/**
 * Référentiel des catégories de vêtements.
 *
 * Chaque ligne encode trois décisions exploitées par l'algorithme de tenues :
 *   - emplacement : quelle "case" du corps la pièce remplit
 *   - couche      : son ordre de superposition (1 = peau, 3 = extérieur)
 *   - obligatoire : une tenue sans pièce de cet emplacement est invalide
 *
 * Les plages de température servent à préremplir le formulaire de capture.
 */
class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ── Haut du corps, première couche ──────────────────────────
            ['T-shirt',     'haut',       null,  1, true,  15,  40],
            ['Débardeur',   'haut',       null,  1, true,  20,  45],
            ['Chemise',     'haut',       null,  1, true,  10,  30],
            ['Blouse',      'haut',       null,  1, true,  12,  30],
            ['Polo',        'haut',       null,  1, true,  15,  35],

            // Une robe couvre le haut ET le bas : l'emplacement secondaire
            // dit à l'algorithme de ne pas chercher de bas en plus.
            ['Robe',        'haut',       'bas', 1, true,  15,  35],

            // ── Haut du corps, deuxième couche ──────────────────────────
            ['Pull',        'haut',       null,  2, true,  -5,  18],
            ['Sweat',       'haut',       null,  2, true,   0,  18],
            ['Gilet',       'haut',       null,  2, true,   5,  20],
            ['Cardigan',    'haut',       null,  2, true,   8,  22],

            // ── Bas du corps ────────────────────────────────────────────
            ['Jean',        'bas',        null,  1, true,  -5,  28],
            ['Pantalon',    'bas',        null,  1, true,  -5,  28],
            ['Chino',       'bas',        null,  1, true,   5,  30],
            ['Short',       'bas',        null,  1, true,  20,  45],
            ['Jupe',        'bas',        null,  1, true,  15,  35],
            ['Legging',     'bas',        null,  1, true,   0,  20],

            // ── Couche extérieure ───────────────────────────────────────
            ['Manteau',     'exterieur',  null,  3, false, -10, 12],
            ['Doudoune',    'exterieur',  null,  3, false, -15,  8],
            ['Imperméable', 'exterieur',  null,  3, false,   0, 20],
            ['Veste',       'exterieur',  null,  3, false,   8, 22],
            ['Blazer',      'exterieur',  null,  3, false,  10, 25],
            ['Trench',      'exterieur',  null,  3, false,   5, 18],

            // ── Chaussures ──────────────────────────────────────────────
            ['Baskets',     'pieds',      null,  1, true,  -5,  35],
            ['Bottines',    'pieds',      null,  1, true,  -5,  20],
            ['Bottes',      'pieds',      null,  1, true, -10,  12],
            ['Sandales',    'pieds',      null,  1, true,  20,  45],
            ['Mocassins',   'pieds',      null,  1, true,  10,  30],
            ['Escarpins',   'pieds',      null,  1, true,  10,  30],

            // ── Accessoires ─────────────────────────────────────────────
            ['Écharpe',     'accessoire', null,  3, false, -10, 12],
            ['Bonnet',      'accessoire', null,  3, false, -10,  8],
            ['Gants',       'accessoire', null,  3, false, -10,  8],
            ['Casquette',   'accessoire', null,  3, false,  15, 45],
            ['Sac',         'accessoire', null,  3, false, -10, 45],
            ['Ceinture',    'accessoire', null,  3, false, -10, 45],
        ];

        foreach ($categories as [$nom, $emplacement, $secondaire, $couche, $obligatoire, $tmin, $tmax]) {
            Categorie::updateOrCreate(
                ['nom' => $nom],
                [
                    'emplacement' => $emplacement,
                    'emplacement_secondaire' => $secondaire,
                    'couche' => $couche,
                    'obligatoire' => $obligatoire,
                    'temp_min_defaut' => $tmin,
                    'temp_max_defaut' => $tmax,
                ]
            );
        }

        $this->command->info(count($categories).' catégories enregistrées.');
    }
}
