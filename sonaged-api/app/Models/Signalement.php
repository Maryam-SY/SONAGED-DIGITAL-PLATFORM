<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'type',
        'urgence',
        'latitude',
        'longitude',
        'adresse',
        'statut',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relations Eloquent
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeUrgents($query)
    {
        return $query->whereIn('urgence', ['haute', 'critique']);
    }
}
