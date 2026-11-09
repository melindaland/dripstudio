<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Amorçage de la base.
 *
 * En mono-utilisateur, l'application travaille avec un unique utilisateur
 * d'identifiant 1, vers lequel pointent tous les user_id. Il est créé ici
 * pour que les clés étrangères soient satisfaites dès le départ, sans
 * attendre que l'écran d'inscription (fonctionnalité bonus) existe.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'melindaaland1@gmail.com'],
            [
                'name' => 'Mélinda ALAND',
                'password' => Hash::make('dripstudio'),
            ]
        );

        Profil::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pseudo' => 'Mélinda',
                'ville_defaut' => 'Belfort',
            ]
        );

        $this->call([
            CategorieSeeder::class,
        ]);

        $this->command->info('Utilisateur et profil par défaut créés.');
    }
}
