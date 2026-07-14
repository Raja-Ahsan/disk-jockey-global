<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\DjSearchService;
use App\Support\BookDjValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function __construct(
        protected DjSearchService $djSearch
    ) {}

    public function index()
    {
        $merchProducts = Product::active()
            ->with('variations', 'productCategory')
            ->get()
            ->filter(fn ($product) => $product->has_stock)
            ->sortByDesc('created_at')
            ->take(4);

        return view('home', compact('merchProducts'));
    }

    public function bookSearch(Request $request)
    {
        if ($request->boolean('search_by_name')) {
            return $this->bookSearchByName($request);
        }

        $validator = Validator::make($request->all(), BookDjValidation::rules());

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        [$budgetMin, $budgetMax] = BookDjValidation::parseBudgetRange($validated['budget_range']);

        $venueTypeLabel = $validated['venue_type'] === 'other'
            ? ($validated['venue_type_other'] ?? 'Other')
            : ucfirst($validated['venue_type']);

        $searchData = [
            'client_name' => $validated['client_name'],
            'city' => $validated['city'],
            'zipcode' => $validated['zipcode'],
            'event_date' => $validated['event_date'],
            'event_type' => $validated['event_type'],
            'venue_type' => $validated['venue_type'],
            'venue_type_other' => $validated['venue_type_other'] ?? null,
            'venue_type_label' => $venueTypeLabel,
            'venue_name' => $validated['venue_name'],
            'venue_address' => $validated['venue_address'],
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'use_near_me' => $request->boolean('use_near_me'),
            'rush_guarantee' => $request->boolean('rush_guarantee'),
            'search_by_name' => $request->boolean('search_by_name'),
            'dj_name' => $validated['dj_name'] ?? null,
        ];

        session(['booking_search' => $searchData]);

        if (Auth::check() && Auth::user()->name !== $validated['client_name']) {
            Auth::user()->update(['name' => $validated['client_name']]);
        }

        $queryParams = [
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'use_near_me' => $searchData['use_near_me'] ? 1 : 0,
            'zipcode' => $validated['zipcode'],
        ];

        $flashMessage = $searchData['use_near_me']
            ? 'DJs within 250 miles of your zip code (matching your budget).'
            : 'DJs matching your budget filter.';

        if (! Auth::check()) {
            $flashMessage .= ' Log in or sign up when you are ready to book.';
        }

        $redirect = route('browse', $queryParams);

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirect, 'message' => $flashMessage]);
        }

        return redirect($redirect)->with('success', $flashMessage);
    }

    public function search(Request $request)
    {
        $params = $this->mergeSearchParams($request);
        $query = $this->djSearch->search($params);
        $djs = $query->paginate(12)->withQueryString();
        $bookingSearch = session('booking_search');

        return view('browse', compact('djs', 'bookingSearch'));
    }

    protected function mergeSearchParams(Request $request): array
    {
        $session = session('booking_search', []);

        return [
            'zipcode' => $request->get('zipcode', $session['zipcode'] ?? null),
            'budget_min' => $request->get('budget_min', $session['budget_min'] ?? null),
            'budget_max' => $request->get('budget_max', $session['budget_max'] ?? null),
            'use_near_me' => $request->boolean('use_near_me', $session['use_near_me'] ?? false),
            'search_by_name' => false,
            'dj_name' => null,
        ];
    }

    protected function bookSearchByName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dj_name' => 'required|string|max:255',
            'event_date' => 'required|date|after_or_equal:today',
            'client_name' => 'nullable|string|max:255',
            'search_by_name' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $dj = $this->djSearch->findByStageName($validated['dj_name']);

        if (! $dj) {
            $message = 'No verified DJ/MC found with that name. Check spelling or use the main Search button.';

            if ($request->expectsJson()) {
                return response()->json(['errors' => ['dj_name' => [$message]]], 422);
            }

            return back()->withErrors(['dj_name' => $message])->withInput();
        }

        $clientName = $validated['client_name']
            ?? (Auth::check() ? Auth::user()->name : null);

        if (Auth::check() && $clientName && Auth::user()->name !== $clientName) {
            Auth::user()->update(['name' => $clientName]);
        }

        session([
            'booking_search' => [
                'client_name' => $clientName ?? 'Guest',
                'event_date' => $validated['event_date'],
                'dj_name' => $validated['dj_name'],
                'search_by_name' => true,
                'search_by_name_only' => true,
            ],
        ]);

        $redirect = route('dj.show', $dj->id);
        $success = 'Showing '.$dj->stage_name.' for your event on '.date('M d, Y', strtotime($validated['event_date'])).'. Complete venue details when you book.';

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirect, 'message' => $success]);
        }

        return redirect($redirect)->with('success', $success);
    }

    public function homeSearch(Request $request)
    {
        $params = $this->mergeSearchParams($request);
        $djs = $this->djSearch->search($params)->limit(3)->get();

        return response()->json($djs);
    }
}
