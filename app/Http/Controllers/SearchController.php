<?php

namespace App\Http\Controllers;

use App\Models\DJ;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $djs = DJ::with('user', 'categories')
            ->available()
            ->verified()
            ->orderBy('rating', 'desc')
            ->limit(3)
            ->get();
        
        return view('home', compact('djs'));
    }

    public function search(Request $request)
    {
        $query = DJ::query()->with('user', 'categories')
            ->available()
            ->verified();

        // Genre filter
        if ($request->filled('genre')) {
            $query->byGenre($request->genre);
        }

        // Price range filter
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->byPriceRange(
                $request->price_min,
                $request->price_max
            );
        }

        // Location filters
        if ($request->filled('city') || $request->filled('state') || $request->filled('zipcode')) {
            $query->byLocation(
                $request->city,
                $request->state,
                $request->zipcode
            );
        }

        // Sort by rating or price
        $sortBy = $request->get('sort_by', 'rating');
        if ($sortBy === 'price_low') {
            $query->orderBy('hourly_rate', 'asc');
        } elseif ($sortBy === 'price_high') {
            $query->orderBy('hourly_rate', 'desc');
        } else {
            $query->orderBy('rating', 'desc');
        }

        $djs = $query->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'djs' => $djs,
                'html' => view('partials.dj-cards', compact('djs'))->render()
            ]);
        }

        return view('browse', compact('djs'));
    }

    public function homeSearch(Request $request)
    {
        $query = DJ::query()->with('user')
            ->available()
            ->verified();

        if ($request->filled('genre')) {
            $query->byGenre($request->genre);
        }

        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->byPriceRange($request->price_min, $request->price_max);
        }

        if ($request->filled('city') || $request->filled('state') || $request->filled('zipcode')) {
            $query->byLocation($request->city, $request->state, $request->zipcode);
        }

        $djs = $query->orderBy('rating', 'desc')->limit(3)->get();

        return response()->json($djs);
    }
}
