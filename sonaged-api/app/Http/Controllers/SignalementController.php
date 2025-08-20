<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Signalement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SignalementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = Signalement::with('user');
        
        // Filtres selon le rôle de l'utilisateur
        if ($user->role === 'citoyen') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'admin') {
            // Les admins voient tous les signalements
        } elseif ($user->role === 'partenaire') {
            // Les partenaires voient les signalements dans leur zone géographique
            $query->where('type', 'dechet'); // Pour l'instant, tous les déchets
        }
        
        // Filtres optionnels
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('urgence')) {
            $query->where('urgence', $request->urgence);
        }
        
        $signalements = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'data' => $signalements->items(),
            'pagination' => [
                'current_page' => $signalements->currentPage(),
                'last_page' => $signalements->lastPage(),
                'per_page' => $signalements->perPage(),
                'total' => $signalements->total()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'required|string',
            'type' => 'required|in:dechet,pollution,autre',
            'urgence' => 'required|in:faible,moyenne,haute,critique',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'adresse' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'string'
        ]);

        $signalement = Signalement::create([
            'user_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => $request->type,
            'urgence' => $request->urgence,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'adresse' => $request->adresse,
            'photos' => $request->photos,
            'statut' => 'en_attente'
        ]);

        // Ajouter des points à l'utilisateur
        $user = Auth::user();
        $points = $this->calculatePoints($request->urgence, $request->type);
        $user->increment('points', $points);

        return response()->json([
            'message' => 'Signalement créé avec succès',
            'data' => $signalement->load('user'),
            'points_gagnes' => $points
        ], 201);
    }

    /**
     * Store a newly created resource in storage (public route - no authentication required).
     */
    public function storePublic(Request $request): JsonResponse
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'required|string',
            'type' => 'required|in:dechet,pollution,autre',
            'urgence' => 'required|in:faible,moyenne,haute,critique',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'adresse' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'string'
        ]);

        // Créer un utilisateur anonyme ou utiliser un utilisateur par défaut
        $anonymousUserId = 1; // ID d'un utilisateur par défaut dans la base de données

        $signalement = Signalement::create([
            'user_id' => $anonymousUserId,
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => $request->type,
            'urgence' => $request->urgence,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'adresse' => $request->adresse,
            'photos' => $request->photos,
            'statut' => 'en_attente'
        ]);

        return response()->json([
            'message' => 'Signalement créé avec succès',
            'data' => $signalement->load('user'),
            'note' => 'Pour gagner des points, connectez-vous à votre compte'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $signalement = Signalement::with('user')->findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'citoyen' && $signalement->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        return response()->json(['data' => $signalement]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $signalement = Signalement::findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'citoyen' && $signalement->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $request->validate([
            'titre' => 'sometimes|string|max:200',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:dechet,pollution,autre',
            'urgence' => 'sometimes|in:faible,moyenne,haute,critique',
            'statut' => 'sometimes|in:en_attente,en_cours,resolu,rejete',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'adresse' => 'sometimes|string|max:255',
            'photos' => 'sometimes|array',
            'photos.*' => 'string'
        ]);
        
        $signalement->update($request->only([
            'titre', 'description', 'type', 'urgence', 'statut',
            'latitude', 'longitude', 'adresse', 'photos'
        ]));
        
        return response()->json([
            'message' => 'Signalement mis à jour avec succès',
            'data' => $signalement->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $signalement = Signalement::findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'citoyen' && $signalement->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        
        $signalement->delete();
        
        return response()->json(['message' => 'Signalement supprimé avec succès']);
    }

    /**
     * Calculer les points selon l'urgence et le type
     */
    private function calculatePoints(string $urgence, string $type): int
    {
        $points = 0;
        
        // Points selon l'urgence
        switch ($urgence) {
            case 'faible':
                $points += 5;
                break;
            case 'moyenne':
                $points += 10;
                break;
            case 'haute':
                $points += 20;
                break;
            case 'critique':
                $points += 50;
                break;
        }
        
        // Bonus selon le type
        switch ($type) {
            case 'dechet':
                $points += 5;
                break;
            case 'pollution':
                $points += 10;
                break;
            case 'autre':
                $points += 3;
                break;
        }
        
        return $points;
    }

    /**
     * Statistiques des signalements
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();
        $query = Signalement::query();
        
        if ($user->role === 'citoyen') {
            $query->where('user_id', $user->id);
        }
        
        $stats = [
            'total' => $query->count(),
            'en_attente' => (clone $query)->where('statut', 'en_attente')->count(),
            'en_cours' => (clone $query)->where('statut', 'en_cours')->count(),
            'resolu' => (clone $query)->where('statut', 'resolu')->count(),
            'rejete' => (clone $query)->where('statut', 'rejete')->count(),
            'par_type' => [
                'dechet' => (clone $query)->where('type', 'dechet')->count(),
                'pollution' => (clone $query)->where('type', 'pollution')->count(),
                'autre' => (clone $query)->where('type', 'autre')->count(),
            ],
            'par_urgence' => [
                'faible' => (clone $query)->where('urgence', 'faible')->count(),
                'moyenne' => (clone $query)->where('urgence', 'moyenne')->count(),
                'haute' => (clone $query)->where('urgence', 'haute')->count(),
                'critique' => (clone $query)->where('urgence', 'critique')->count(),
            ]
        ];
        
        return response()->json(['data' => $stats]);
    }
}
