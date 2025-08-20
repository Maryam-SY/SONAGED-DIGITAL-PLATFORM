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
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->enum('type', ['dechet', 'pollution', 'autre'])->default('dechet');
            $table->enum('urgence', ['faible', 'moyenne', 'haute', 'critique'])->default('moyenne');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('adresse')->nullable();
            $table->enum('statut', ['en_attente', 'en_cours', 'resolu', 'rejete'])->default('en_attente');
            $table->json('photos')->nullable();
            $table->json('ia_analysis')->nullable();
            $table->timestamps();
            
            $table->index(['latitude', 'longitude']);
            $table->index('statut');
            $table->index('urgence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
