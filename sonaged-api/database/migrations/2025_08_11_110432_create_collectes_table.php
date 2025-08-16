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
        Schema::create('collectes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('partenaire_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type_dechet', ['plastique', 'verre', 'papier', 'metal', 'organique', 'autre']);
            $table->decimal('quantite', 8, 2);
            $table->string('unite', 20)->default('kg');
            $table->enum('statut', ['planifiee', 'en_cours', 'terminee', 'annulee'])->default('planifiee');
            $table->date('date_collecte');
            $table->time('heure_collecte');
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'statut']);
            $table->index(['partenaire_id', 'date_collecte']);
            $table->index(['type_dechet', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};
