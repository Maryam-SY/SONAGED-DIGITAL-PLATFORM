<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $fillable = [
        'nom_entreprise',
        'description',
        'email',
        'telephone',
        'adresse',
        'latitude',
        'longitude',
        'type',
        'statut',
        'horaires',
        'services',
    ];

    protected $casts = [
        'horaires' => 'array',
        'services' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relations Eloquent
     */
    public function collectes()
    {
        return $this->hasMany(Collecte::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }
}
