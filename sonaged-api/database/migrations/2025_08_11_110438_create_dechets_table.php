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
        Schema::create('dechets', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->text('description');
            $table->enum('categorie', ['plastique', 'verre', 'papier', 'metal', 'organique', 'electronique', 'autre']);
            $table->enum('niveau_danger', ['faible', 'moyen', 'eleve', 'critique']);
            $table->string('code_recyclage', 10)->nullable();
            $table->json('instructions_traitement')->nullable();
            $table->json('alternatives_ecologiques')->nullable();
            $table->boolean('recyclable')->default(true);
            $table->timestamps();
            
            $table->index(['categorie', 'niveau_danger']);
            $table->index('recyclable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dechets');
    }
};
