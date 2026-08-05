<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class LandingpageController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer ou définir la langue
        $locale = $request->get('lang', session('locale', 'en'));
        App::setLocale($locale);
        session(['locale' => $locale]);

        // Statistiques
        $stats = [
            'total_donations' => Donation::count(),
            'total_families' => User::where('role', 'recipient')->count(),
            'total_weight' => Donation::sum('quantity') ?? 0,
        ];

        // Récupérer les donations disponibles (les 6 plus récentes)
        $donations = Donation::with('user')
            ->where('status', 'available')
            ->where('expiry_date', '>=', now())
            ->latest()
            ->take(6)
            ->get();

        // Récupérer toutes les donations avec coordonnées pour la carte
        $mapDonations = Donation::with('user')
            ->where('status', 'available')
            ->where('expiry_date', '>=', now())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'title' => $donation->food_type,
                    'description' => $donation->description,
                    'quantity' => $donation->quantity,
                    'address' => $donation->address,
                    'latitude' => (float) $donation->latitude,
                    'longitude' => (float) $donation->longitude,
                    'donor_name' => $donation->user->name ?? 'Anonymous',
                    'expires' => $donation->expiry_date->format('Y-m-d'),
                ];
            });

        return view('landingpage', compact('stats', 'donations', 'mapDonations'));
    }

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