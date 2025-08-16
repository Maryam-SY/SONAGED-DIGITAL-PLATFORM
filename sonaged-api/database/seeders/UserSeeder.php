<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::create([
            'name' => 'Admin SONAGED',
            'nom' => 'Admin SONAGED',
            'email' => 'admin@sonaged.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'points' => 1000,
            'telephone' => '+237 6XX XXX XXX',
            'preferences' => [
                'notifications' => true,
                'theme' => 'dark',
                'langue' => 'fr'
            ]
        ]);

        // Créer un utilisateur citoyen
        User::create([
            'name' => 'Jean Dupont',
            'nom' => 'Jean Dupont',
            'email' => 'jean.dupont@email.com',
            'password' => Hash::make('password123'),
            'role' => 'citoyen',
            'points' => 150,
            'telephone' => '+237 6XX XXX XXX',
            'preferences' => [
                'notifications' => true,
                'theme' => 'light',
                'langue' => 'fr'
            ]
        ]);

        // Créer un utilisateur partenaire
        User::create([
            'name' => 'EcoRecycle SARL',
            'nom' => 'EcoRecycle SARL',
            'email' => 'contact@ecorecycle.com',
            'password' => Hash::make('password123'),
            'role' => 'partenaire',
            'points' => 500,
            'telephone' => '+237 6XX XXX XXX',
            'preferences' => [
                'notifications' => true,
                'theme' => 'light',
                'langue' => 'fr'
            ]
        ]);

        $this->command->info('Users créés avec succès !');
    }
}
