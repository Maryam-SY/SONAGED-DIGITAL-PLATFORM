<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Collecte;
use App\Models\Partenaire;
use Illuminate\Support\Facades\Auth;

class CollecteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = Collecte::with(['user', 'partenaire']);
        
        // Filtres selon le rôle
        if ($user->role === 'citoyen') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'partenaire') {
            // Les partenaires voient les collectes qui leur sont assignées
            $query->where('partenaire_id', $user->id);
        }
        
        // Filtres optionnels
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('type_dechet')) {
            $query->where('type_dechet', $request->type_dechet);
        }
        
        if ($request->has('date_collecte')) {
            $query->whereDate('date_collecte', $request->date_collecte);
        }
        
        $collectes = $query->orderBy('date_collecte', 'desc')->paginate(15);
        
        return response()->json([
            'data' => $collectes->items(),
            'pagination' => [
                'current_page' => $collectes->currentPage(),
                'last_page' => $collectes->lastPage(),
                'per_page' => $collectes->perPage(),
                'total' => $collectes->total()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'partenaire_id' => 'required|exists:partenaires,id',
            'type_dechet' => 'required|string|max:100',
            'quantite' => 'required|numeric|min:0.1',
            'unite' => 'required|in:kg,litres,pieces,metres',
            'date_collecte' => 'required|date|after:today',
            'heure_collecte' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'string'
        ]);
        
        // Vérifier que le partenaire est actif
        $partenaire = Partenaire::findOrFail($request->partenaire_id);
        if ($partenaire->statut !== 'actif') {
            return response()->json([
                'message' => 'Ce partenaire n\'est pas disponible pour les collectes'
            ], 400);
        }
        
        $collecte = Collecte::create([
            'user_id' => Auth::id(),
            'partenaire_id' => $request->partenaire_id,
            'type_dechet' => $request->type_dechet,
            'quantite' => $request->quantite,
            'unite' => $request->unite,
            'date_collecte' => $request->date_collecte,
            'heure_collecte' => $request->heure_collecte,
            'notes' => $request->notes,
            'photos' => $request->photos,
            'statut' => 'planifiee'
        ]);
        
        // Ajouter des points à l'utilisateur
        $points = $this->calculatePoints($request->type_dechet, $request->quantite);
        Auth::user()->increment('points', $points);
        
        return response()->json([
            'message' => 'Collecte planifiée avec succès',
            'data' => $collecte->load(['user', 'partenaire']),
            'points_gagnes' => $points
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $collecte = Collecte::with(['user', 'partenaire'])->findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'citoyen' && $collecte->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        return response()->json(['data' => $collecte]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $collecte = Collecte::findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'citoyen' && $collecte->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $request->validate([
            'type_dechet' => 'sometimes|string|max:100',
            'quantite' => 'sometimes|numeric|min:0.1',
            'unite' => 'sometimes|in:kg,litres,pieces,metres',
            'date_collecte' => 'sometimes|date|after:today',
            'heure_collecte' => 'sometimes|date_format:H:i',
            'statut' => 'sometimes|in:planifiee,en_cours,terminee,annulee',
            'notes' => 'sometimes|string',
            'photos' => 'sometimes|array',
            'photos.*' => 'string'
        ]);
        
        $collecte->update($request->all());
        
        return response()->json([
            'message' => 'Collecte mise à jour avec succès',
            'data' => $collecte->load(['user', 'partenaire'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $collecte = Collecte::findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'citoyen' && $collecte->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        // Seules les collectes planifiées peuvent être supprimées
        if ($collecte->statut !== 'planifiee') {
            return response()->json([
                'message' => 'Seules les collectes planifiées peuvent être supprimées'
            ], 400);
        }
        
        $collecte->delete();
        
        return response()->json(['message' => 'Collecte supprimée avec succès']);
    }

    /**
     * Calculer les points selon le type de déchet et la quantité
     */
    private function calculatePoints(string $typeDechet, float $quantite): int
    {
        $points = 0;
        
        // Points de base selon le type de déchet
        switch (strtolower($typeDechet)) {
            case 'plastique':
            case 'verre':
            case 'papier':
                $points += 10;
                break;
            case 'metal':
            case 'aluminium':
                $points += 15;
                break;
            case 'electronique':
            case 'batterie':
                $points += 25;
                break;
            default:
                $points += 5;
        }
        
        // Bonus selon la quantité (1 point par kg/litre)
        $points += (int)($quantite * 1);
        
        return $points;
    }

    /**
     * Confirmer une collecte (pour les partenaires)
     */
    public function confirm(string $id): JsonResponse
    {
        $collecte = Collecte::findOrFail($id);
        
        // Seuls les partenaires peuvent confirmer les collectes
        if (Auth::user()->role !== 'partenaire') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        if ($collecte->statut !== 'planifiee') {
            return response()->json([
                'message' => 'Seules les collectes planifiées peuvent être confirmées'
            ], 400);
        }
        
        $collecte->update(['statut' => 'en_cours']);
        
        return response()->json([
            'message' => 'Collecte confirmée et en cours',
            'data' => $collecte->load(['user', 'partenaire'])
        ]);
    }

    /**
     * Terminer une collecte
     */
    public function complete(string $id): JsonResponse
    {
        $collecte = Collecte::findOrFail($id);
        
        // Seuls les partenaires peuvent terminer les collectes
        if (Auth::user()->role !== 'partenaire') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        if ($collecte->statut !== 'en_cours') {
            return response()->json([
                'message' => 'Seules les collectes en cours peuvent être terminées'
            ], 400);
        }
        
        $collecte->update(['statut' => 'terminee']);
        
        return response()->json([
            'message' => 'Collecte terminée avec succès',
            'data' => $collecte->load(['user', 'partenaire'])
        ]);
    }
}
