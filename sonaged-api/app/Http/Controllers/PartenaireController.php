<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Partenaire;
use Illuminate\Support\Facades\Auth;

class PartenaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Partenaire::query();
        
        // Filtres
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_entreprise', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('adresse', 'like', "%{$search}%");
            });
        }
        
        $partenaires = $query->where('statut', 'actif')
                            ->orderBy('nom_entreprise')
                            ->paginate(15);
        
        return response()->json([
            'data' => $partenaires->items(),
            'pagination' => [
                'current_page' => $partenaires->currentPage(),
                'last_page' => $partenaires->lastPage(),
                'per_page' => $partenaires->perPage(),
                'total' => $partenaires->total()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Seuls les admins peuvent créer des partenaires
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $request->validate([
            'nom_entreprise' => 'required|string|max:200',
            'description' => 'required|string',
            'email' => 'required|email|unique:partenaires',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'type' => 'required|in:recyclage,collecte,traitement,autre',
            'horaires' => 'nullable|array',
            'services' => 'nullable|array'
        ]);
        
        $partenaire = Partenaire::create($request->all());
        
        return response()->json([
            'message' => 'Partenaire créé avec succès',
            'data' => $partenaire
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $partenaire = Partenaire::with('collectes')->findOrFail($id);
        
        return response()->json(['data' => $partenaire]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $partenaire = Partenaire::findOrFail($id);
        
        // Seuls les admins peuvent modifier les partenaires
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $request->validate([
            'nom_entreprise' => 'sometimes|string|max:200',
            'description' => 'sometimes|string',
            'email' => 'sometimes|email|unique:partenaires,email,' . $id,
            'telephone' => 'sometimes|string|max:20',
            'adresse' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'type' => 'sometimes|in:recyclage,collecte,traitement,autre',
            'statut' => 'sometimes|in:actif,inactif,en_attente',
            'horaires' => 'sometimes|array',
            'services' => 'sometimes|array'
        ]);
        
        $partenaire->update($request->all());
        
        return response()->json([
            'message' => 'Partenaire mis à jour avec succès',
            'data' => $partenaire
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $partenaire = Partenaire::findOrFail($id);
        
        // Seuls les admins peuvent supprimer les partenaires
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        // Vérifier s'il y a des collectes liées
        if ($partenaire->collectes()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer ce partenaire car il a des collectes associées'
            ], 400);
        }
        
        $partenaire->delete();
        
        return response()->json(['message' => 'Partenaire supprimé avec succès']);
    }

    /**
     * Recherche de partenaires par géolocalisation
     */
    public function nearby(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:50', // Rayon en km
            'type' => 'nullable|string'
        ]);
        
        $radius = $request->radius ?? 10; // Rayon par défaut : 10km
        
        $partenaires = Partenaire::selectRaw('*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', 
            [$request->latitude, $request->longitude, $request->latitude])
            ->where('statut', 'actif')
            ->when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
        
        return response()->json(['data' => $partenaires]);
    }
}
