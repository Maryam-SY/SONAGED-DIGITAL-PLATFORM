<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dechet extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'categorie',
        'niveau_danger',
        'code_recyclage',
        'instructions_traitement',
        'alternatives_ecologiques',
        'recyclable',
    ];

    protected $casts = [
        'instructions_traitement' => 'array',
        'alternatives_ecologiques' => 'array',
        'recyclable' => 'boolean',
    ];

    /**
     * Relations Eloquent
     */
    public function scopeRecyclables($query)
    {
        return $query->where('recyclable', true);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeParNiveauDanger($query, $niveau)
    {
        return $query->where('niveau_danger', $niveau);
    }
}
