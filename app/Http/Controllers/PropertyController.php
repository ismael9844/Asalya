<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\CloudStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    protected $uploader;

    public function __construct(CloudStorageService $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Affiche la liste de toutes les propriétés
     */
    public function index()
    {
        // Utiliser paginate() au lieu de get() pour activer la pagination
        $properties = Property::latest()->paginate(12); // 12 propriétés par page
        return view('properties.index', compact('properties'));
    }

    /**
     * Affiche le formulaire de création d'une propriété (admin uniquement)
     */
    public function create(Request $request)
    {
        return view('properties.create');
    }

    /**
     * Enregistre une nouvelle propriété (admin uniquement)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'surface' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,sold,rented',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:30720', // 30MB max pour ImgBB
        ]);

        // Upload des images vers ImgBB
        $imageUrls = [];
        
        if ($request->hasFile('images')) {
            try {
                $imageUrls = $this->uploader->uploadMultiple($request->file('images'));
                
                if (empty($imageUrls)) {
                    return back()
                        ->withErrors(['images' => 'Failed to upload images. Please try again.'])
                        ->withInput();
                }
                
                Log::info('Images uploaded', ['count' => count($imageUrls), 'urls' => $imageUrls]);
            } catch (\Exception $e) {
                Log::error('Image upload error', ['error' => $e->getMessage()]);
                return back()
                    ->withErrors(['images' => 'Error uploading images: ' . $e->getMessage()])
                    ->withInput();
            }
        }

        // Créer la propriété avec les URLs des images
        // NB: 'images' est casté en array dans le modèle Property, on passe donc
        // directement le tableau (pas de json_encode manuel, sinon double encodage)
        $property = Property::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'address' => $validated['address'] ?? null,
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'surface' => $validated['surface'] ?? null,
            'status' => $validated['status'] ?? 'available',
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'images' => $imageUrls,
        ]);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property created successfully with ' . count($imageUrls) . ' image(s)!');
    }

    /**
     * Affiche le formulaire d'édition d'une propriété (admin uniquement)
     */
    public function edit(Request $request, Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Met à jour une propriété existante (admin uniquement)
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'surface' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,sold,rented',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'keep_images' => 'nullable|array', // Pour garder certaines images existantes
        ]);

        // Récupérer les images existantes (déjà un tableau grâce au cast 'array' du modèle)
        $existingImages = is_array($property->images) ? $property->images : (json_decode($property->images, true) ?? []);
        
        // Si keep_images est fourni, garder seulement ces images
        if ($request->has('keep_images')) {
            $imagesToKeep = $request->input('keep_images', []);
            $existingImages = array_filter($existingImages, function($image, $index) use ($imagesToKeep) {
                return in_array($index, $imagesToKeep);
            }, ARRAY_FILTER_USE_BOTH);
            $existingImages = array_values($existingImages); // Réindexer
        }
        
        // Upload de nouvelles images vers le stockage cloud
        $newImageUrls = [];
        if ($request->hasFile('images')) {
            try {
                $newImageUrls = $this->uploader->uploadMultiple($request->file('images'));

                if (empty($newImageUrls)) {
                    return back()
                        ->withErrors(['images' => 'Failed to upload the new image(s). Please check your storage configuration and try again.'])
                        ->withInput();
                }

                Log::info('New images uploaded', ['count' => count($newImageUrls)]);
            } catch (\Exception $e) {
                Log::error('Image upload error during update', ['error' => $e->getMessage()]);
                return back()
                    ->withErrors(['images' => 'Error uploading new images: ' . $e->getMessage()])
                    ->withInput();
            }
        }
        
        // Combiner les anciennes et nouvelles images
        $allImages = array_merge($existingImages, $newImageUrls);

        // Mettre à jour la propriété
        $property->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'address' => $validated['address'] ?? null,
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'surface' => $validated['surface'] ?? null,
            'status' => $validated['status'] ?? 'available',
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'images' => $allImages,
        ]);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Supprime une propriété (admin uniquement)
     */
    public function destroy(Request $request, Property $property)
    {
        // Note: Les images sur Cloudinary restent stockées (pas de suppression automatique)
        
        $property->delete();
        
        return redirect()
            ->route('properties.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Affiche les détails d'une propriété
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);
        
        // Les images sont déjà un tableau grâce au cast 'array' du modèle
        $images = is_array($property->images) ? $property->images : (json_decode($property->images, true) ?? []);
        
        return view('properties.show', compact('property', 'images'));
    }

    /**
     * Landing page avec carte interactive et multilangue
     */
    public function landing(Request $request)
    {
        // Gérer la langue
        $locale = $request->get('lang', session('locale', 'en'));
        App::setLocale($locale);
        session(['locale' => $locale]);

        // Statistiques
        $stats = [
            'total_properties' => Property::count(),
            'available_properties' => Property::where('status', 'available')->count(),
            'total_surface' => Property::sum('surface') ?? 0,
        ];

        // Récupérer les propriétés disponibles (pour l'aperçu)
        $properties = Property::where('status', 'available')
            ->latest()
            ->get()
            ->map(function ($property) {
                // Les images sont déjà un tableau grâce au cast 'array' du modèle
                $images = is_array($property->images) ? $property->images : (json_decode($property->images, true) ?? []);
                $property->image = $images[0] ?? null; // Ajouter l'image principale
                $property->images_array = $images; // Garder toutes les images
                return $property;
            });

        // Pour la compatibilité avec le code existant, on garde aussi $mapProperties
        $mapProperties = $properties;

        return view('landing', compact('stats', 'properties', 'mapProperties'));
    }

    /**
     * API endpoint pour récupérer les propriétés avec filtres
     */
    public function apiProperties(Request $request)
    {
        $query = Property::where('status', 'available');

        // Filtrage par recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par type
        if ($request->has('property_type') && $request->property_type) {
            $query->where('type', $request->property_type);
        }

        // Filtrage par prix
        if ($request->has('price_range') && $request->price_range) {
            $range = $request->price_range;
            if ($range === '1000000+') {
                $query->where('price', '>=', 1000000);
            } else {
                [$min, $max] = explode('-', $range);
                $query->whereBetween('price', [(int)$min, (int)$max]);
            }
        }

        // Filtrage par chambres
        if ($request->has('bedrooms') && $request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'date_new':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $properties = $query->get()->map(function ($property) {
            $images = is_array($property->images) ? $property->images : (json_decode($property->images, true) ?? []);
            return [
                'id' => $property->id,
                'title' => $property->title,
                'type' => $property->type,
                'description' => $property->description,
                'price' => $property->price,
                'address' => $property->address,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'area' => $property->surface,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
                'status' => $property->status,
                'image' => $images[0] ?? null,
                'images' => $images,
            ];
        });

        return response()->json($properties);
    }

    /**
     * Changer la langue
     */
    public function setLanguage(Request $request)
    {
        $locale = $request->input('lang', 'en');
        
        if (in_array($locale, ['en', 'tr'])) {
            session(['locale' => $locale]);
            App::setLocale($locale);
        }

        return redirect()->back();
    }

}