<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Signalement;
use App\Models\User;

class SignalementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'citoyen')->first();

        if ($user) {
            // Créer des signalements de test
            Signalement::create([
                'user_id' => $user->id,
                'titre' => 'Déchets plastiques abandonnés',
                'description' => 'Grande quantité de bouteilles plastiques abandonnées dans le parc municipal',
                'type' => 'dechet',
                'urgence' => 'moyenne',
                'latitude' => 3.848033,
                'longitude' => 11.502075,
                'adresse' => 'Parc Municipal, Yaoundé',
                'statut' => 'en_attente',
                'photos' => ['photo1.jpg', 'photo2.jpg']
            ]);

            Signalement::create([
                'user_id' => $user->id,
                'titre' => 'Pollution des eaux',
                'description' => 'Déversement d\'huiles usées dans le ruisseau',
                'type' => 'pollution',
                'urgence' => 'elevee',
                'latitude' => 3.850000,
                'longitude' => 11.500000,
                'adresse' => 'Ruisseau de la ville, Yaoundé',
                'statut' => 'en_cours',
                'photos' => ['pollution1.jpg']
            ]);

            Signalement::create([
                'user_id' => $user->id,
                'titre' => 'Poubelle publique pleine',
                'description' => 'Poubelle publique débordante nécessitant une vidange urgente',
                'type' => 'dechet',
                'urgence' => 'faible',
                'latitude' => 3.845000,
                'longitude' => 11.505000,
                'adresse' => 'Place de l\'Indépendance, Yaoundé',
                'statut' => 'resolu',
                'photos' => ['poubelle1.jpg']
            ]);

            $this->command->info('Signalements créés avec succès !');
        } else {
            $this->command->error('Aucun utilisateur citoyen trouvé pour créer les signalements');
        }
    }
}
