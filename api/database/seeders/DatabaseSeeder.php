<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 *
 * L'application prend en compte un seul utilisateur par défaut
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
