<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    protected $fillable = [
        'user_id',
        'partenaire_id',
        'type_dechet',
        'quantite',
        'unite',
        'statut',
        'date_collecte',
        'heure_collecte',
        'notes',
        'photos',
    ];

    protected $casts = [
        'date_collecte' => 'date',
        'heure_collecte' => 'datetime',
        'photos' => 'array',
        'quantite' => 'decimal:2',
    ];

    /**
     * Relations Eloquent
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function scopePlanifiees($query)
    {
        return $query->where('statut', 'planifiee');
    }

    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminee');
    }
}
