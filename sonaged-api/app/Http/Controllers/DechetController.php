<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Dechet;
use Illuminate\Support\Facades\Auth;

class DechetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Dechet::query();
        
        // Filtres
        if ($request->has('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->has('niveau_danger')) {
            $query->where('niveau_danger', $request->niveau_danger);
        }
        
        if ($request->has('recyclable')) {
            $query->where('recyclable', $request->recyclable === 'true');
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('code_recyclage', 'like', "%{$search}%");
            });
        }
        
        $dechets = $query->orderBy('nom')->paginate(20);
        
        return response()->json([
            'data' => $dechets->items(),
            'pagination' => [
                'current_page' => $dechets->currentPage(),
                'last_page' => $dechets->lastPage(),
                'per_page' => $dechets->perPage(),
                'total' => $dechets->total()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Seuls les admins peuvent créer des types de déchets
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $request->validate([
            'nom' => 'required|string|max:200|unique:dechets',
            'description' => 'required|string',
            'categorie' => 'required|string|max:100',
            'niveau_danger' => 'required|in:faible,moyen,eleve,critique',
            'code_recyclage' => 'nullable|string|max:50',
            'instructions_traitement' => 'required|array',
            'alternatives_ecologiques' => 'nullable|array',
            'recyclable' => 'required|boolean'
        ]);
        
        $dechet = Dechet::create($request->all());
        
        return response()->json([
            'message' => 'Type de déchet créé avec succès',
            'data' => $dechet
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $dechet = Dechet::findOrFail($id);
        
        return response()->json(['data' => $dechet]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $dechet = Dechet::findOrFail($id);
        
        // Seuls les admins peuvent modifier les types de déchets
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $request->validate([
            'nom' => 'sometimes|string|max:200|unique:dechets,nom,' . $id,
            'description' => 'sometimes|string',
            'categorie' => 'sometimes|string|max:100',
            'niveau_danger' => 'sometimes|in:faible,moyen,eleve,critique',
            'code_recyclage' => 'sometimes|string|max:50',
            'instructions_traitement' => 'sometimes|array',
            'alternatives_ecologiques' => 'sometimes|array',
            'recyclable' => 'sometimes|boolean'
        ]);
        
        $dechet->update($request->all());
        
        return response()->json([
            'message' => 'Type de déchet mis à jour avec succès',
            'data' => $dechet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $dechet = Dechet::findOrFail($id);
        
        // Seuls les admins peuvent supprimer les types de déchets
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $dechet->delete();
        
        return response()->json(['message' => 'Type de déchet supprimé avec succès']);
    }

    /**
     * Recherche avancée de déchets
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $dechets = Dechet::where('nom', 'like', "%{$request->query}%")
            ->orWhere('description', 'like', "%{$request->query}%")
            ->orWhere('code_recyclage', 'like', "%{$request->query}%")
            ->orWhere('categorie', 'like', "%{$request->query}%")
            ->limit(10)
            ->get();
        
        return response()->json(['data' => $dechets]);
    }

    /**
     * Obtenir les catégories de déchets
     */
    public function categories(): JsonResponse
    {
        $categories = Dechet::distinct()->pluck('categorie')->sort()->values();
        
        return response()->json(['data' => $categories]);
    }

    /**
     * Obtenir les déchets recyclables
     */
    public function recyclables(): JsonResponse
    {
        $dechets = Dechet::where('recyclable', true)
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get();
        
        return response()->json(['data' => $dechets]);
    }
}
