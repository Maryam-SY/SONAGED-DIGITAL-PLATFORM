<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un compte administrateur par défaut
        User::create([
            'nom' => 'Administrateur SONAGED',
            'name' => 'Administrateur SONAGED',
            'email' => 'admin@sonaged.sn',
            'password' => Hash::make('admin123'),
            'telephone' => '+221 33 123 45 67',
            'role' => 'admin',
            'points' => 0,
            'preferences' => [
                'notifications' => true,
                'theme' => 'dark',
                'langue' => 'fr'
            ]
        ]);

        // Créer un compte partenaire de test
        User::create([
            'nom' => 'Partenaire Test',
            'name' => 'Partenaire Test',
            'email' => 'partenaire@sonaged.sn',
            'password' => Hash::make('partenaire123'),
            'telephone' => '+221 33 987 65 43',
            'role' => 'partenaire',
            'points' => 0,
            'preferences' => [
                'notifications' => true,
                'theme' => 'light',
                'langue' => 'fr'
            ]
        ]);

        // Créer un compte citoyen de test
        User::create([
            'nom' => 'Citoyen Test',
            'name' => 'Citoyen Test',
            'email' => 'citoyen@sonaged.sn',
            'password' => Hash::make('citoyen123'),
            'telephone' => '+221 77 123 45 67',
            'role' => 'citoyen',
            'points' => 100,
            'preferences' => [
                'notifications' => true,
                'theme' => 'light',
                'langue' => 'fr'
            ]
        ]);

        $this->command->info('Comptes de test créés avec succès !');
        $this->command->info('Admin: admin@sonaged.sn / admin123');
        $this->command->info('Partenaire: partenaire@sonaged.sn / partenaire123');
        $this->command->info('Citoyen: citoyen@sonaged.sn / citoyen123');
    }
}
