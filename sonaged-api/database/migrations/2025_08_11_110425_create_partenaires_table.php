<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom_entreprise', 200);
            $table->text('description');
            $table->string('email', 150)->unique();
            $table->string('telephone', 20);
            $table->string('adresse', 255);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('type', ['recyclage', 'collecte', 'traitement', 'autre']);
            $table->enum('statut', ['actif', 'inactif', 'en_attente'])->default('en_attente');
            $table->json('horaires')->nullable();
            $table->json('services')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'statut']);
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
