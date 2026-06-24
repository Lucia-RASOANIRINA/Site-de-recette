<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRecetteController extends Controller
{
    public function index()
    {
        try {
            $recettes = Recette::where('user_id', Auth::id())
                ->with(['ingredients', 'likes'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('page.recettes', compact('recettes'));
        } catch (\Exception $e) {
            Log::error('Erreur index recettes: ' . $e->getMessage());
            return back()->with('error', 'Erreur de chargement des recettes');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:150',
                'description' => 'required|string',
                'instructions' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ingredients' => 'required|array|min:1',
                'ingredients.*.nom' => 'required|string|max:100',
                'ingredients.*.quantite' => 'required|string|max:50',
            ]);

            // Anti-redondance : pas deux recettes avec le meme titre pour un meme utilisateur
            $exists = Recette::where('user_id', Auth::id())
                ->whereRaw('LOWER(titre) = ?', [mb_strtolower(trim($request->titre))])
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez deja une recette avec ce titre.'
                ], 422);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('recettes', 'public');
            }

            if (!$imagePath) {
                return response()->json([
                    'success' => false,
                    'message' => 'L image est requise'
                ], 422);
            }

            $recette = Recette::create([
                'user_id' => Auth::id(),
                'titre' => $request->titre,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'image_path' => $imagePath,
            ]);

            foreach ($request->ingredients as $ingredient) {
                if (!empty($ingredient['nom']) && !empty($ingredient['quantite'])) {
                    Ingredient::create([
                        'recette_id' => $recette->id,
                        'nom' => $ingredient['nom'],
                        'quantite' => $ingredient['quantite'],
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Recette cree avec succes !',
                'recette' => $recette->load('ingredients')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur creation recette: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $recette = Recette::where('user_id', Auth::id())->findOrFail($id);

            // Validation des données
            $rules = [
                'titre' => 'sometimes|required|string|max:150',
                'description' => 'sometimes|required|string',
                'instructions' => 'sometimes|required|string',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ingredients' => 'sometimes|array|min:1',
                'ingredients.*.nom' => 'required|string|max:100',
                'ingredients.*.quantite' => 'required|string|max:50',
            ];

            $validated = $request->validate($rules);

            $updateData = [];
            $hasChanges = false;

            // Mettre à jour le titre si présent
            if ($request->has('titre')) {
                $updateData['titre'] = $request->titre;
                $hasChanges = true;
            }

            // Mettre à jour la description si présente
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
                $hasChanges = true;
            }

            // Mettre à jour les instructions si présentes
            if ($request->has('instructions')) {
                $updateData['instructions'] = $request->instructions;
                $hasChanges = true;
            }

            // Mettre à jour l'image si présente
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($recette->image_path && Storage::disk('public')->exists($recette->image_path)) {
                    Storage::disk('public')->delete($recette->image_path);
                }
                $imagePath = $request->file('image')->store('recettes', 'public');
                $updateData['image_path'] = $imagePath;
                $hasChanges = true;
            }

            // Exécuter la mise à jour
            if (!empty($updateData)) {
                $recette->update($updateData);
            }

            // Mettre à jour les ingrédients si présents
            if ($request->has('ingredients') && is_array($request->ingredients)) {
                // Supprimer les anciens ingrédients
                $recette->ingredients()->delete();
                
                // Ajouter les nouveaux ingrédients
                $newIngredients = [];
                foreach ($request->ingredients as $item) {
                    if (!empty($item['nom']) && !empty($item['quantite'])) {
                        $newIngredients[] = [
                            'recette_id' => $recette->id,
                            'nom' => $item['nom'],
                            'quantite' => $item['quantite'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($newIngredients)) {
                    Ingredient::insert($newIngredients);
                    $hasChanges = true;
                }
            }

            if (!$hasChanges) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune modification detectee'
                ], 422);
            }

            $recette->refresh();
            $recette->load('ingredients');

            return response()->json([
                'success' => true,
                'message' => 'Recette mise a jour avec succes !',
                'recette' => $recette
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur mise a jour recette: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $query = Recette::query();
            if (!Auth::user()->isAdmin()) {
                $query->where('user_id', Auth::id());
            }
            $recette = $query->findOrFail($id);

            if ($recette->image_path && Storage::disk('public')->exists($recette->image_path)) {
                Storage::disk('public')->delete($recette->image_path);
            }
            
            $recette->ingredients()->delete();
            $recette->likes()->delete();
            $recette->delete();

            return response()->json([
                'success' => true,
                'message' => 'Recette supprimee avec succes !'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression recette: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $recette = Recette::where('user_id', Auth::id())
                ->with(['ingredients', 'likes'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'recette' => $recette
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvee'
            ], 404);
        }
    }
}