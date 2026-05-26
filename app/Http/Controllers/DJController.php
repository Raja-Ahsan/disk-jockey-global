<?php

namespace App\Http\Controllers;

use App\Models\DJ;
use App\Models\Category;
use App\Services\DjSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DJController extends Controller
{
    public function __construct(
        protected DjSearchService $djSearch
    ) {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $params = [
            'zipcode' => $request->get('zipcode', session('booking_search.zipcode')),
            'budget_min' => $request->get('budget_min', session('booking_search.budget_min')),
            'budget_max' => $request->get('budget_max', session('booking_search.budget_max')),
            'use_near_me' => $request->boolean('use_near_me', session('booking_search.use_near_me', false)),
            'search_by_name' => false,
            'dj_name' => null,
        ];

        $query = $this->djSearch->search($params);
        $djs = $query->paginate(12)->withQueryString();
        $bookingSearch = session('booking_search');

        return view('browse', compact('djs', 'bookingSearch'));
    }

    public function show($id)
    {
        $dj = DJ::with('user', 'categories', 'reviews.user')
            ->available()
            ->verified()
            ->findOrFail($id);

        $bookingSearch = session('booking_search');

        return view('dj.show', compact('dj', 'bookingSearch'));
    }

    public function create()
    {
        if (! Auth::user()->isDJ()) {
            return redirect()->route('register')->with('error', 'Please register as a DJ first.');
        }

        if (Auth::user()->dj) {
            return redirect()->route('dj.edit', Auth::user()->dj->id);
        }

        $categories = Category::where('is_active', true)->get();

        return view('dj.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'hourly_rate' => 'required|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'specialties' => 'nullable|array',
            'genres' => 'nullable|array',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'equipment' => 'nullable|string',
            'categories' => 'nullable|array',
        ]);

        $profileImage = null;
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image')->store('dj-profiles', 'public');
        }

        $dj = DJ::create([
            'user_id' => Auth::id(),
            'stage_name' => $request->stage_name,
            'bio' => $request->bio,
            'profile_image' => $profileImage,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'hourly_rate' => $request->hourly_rate,
            'experience_years' => $request->experience_years ?? 0,
            'specialties' => $request->specialties ?? [],
            'genres' => $request->genres ?? [],
            'phone' => $request->phone,
            'website' => $request->website,
            'equipment' => $request->equipment,
            'is_verified' => false,
            'is_available' => true,
        ]);

        if ($request->categories) {
            $dj->categories()->sync($request->categories);
        }

        return redirect()->route('dj.dashboard')->with('success', 'DJ profile created! Awaiting admin verification.');
    }

    public function edit($id)
    {
        $dj = DJ::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::where('is_active', true)->get();

        return view('dj.edit', compact('dj', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $dj = DJ::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'stage_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'hourly_rate' => 'required|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'specialties' => 'nullable|array',
            'genres' => 'nullable|array',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'equipment' => 'nullable|string',
            'is_available' => 'boolean',
            'categories' => 'nullable|array',
        ]);

        $data = $request->only([
            'stage_name', 'bio', 'city', 'state', 'zipcode', 'hourly_rate',
            'experience_years', 'specialties', 'genres', 'phone', 'website', 'equipment',
        ]);

        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('profile_image')) {
            if ($dj->profile_image) {
                Storage::disk('public')->delete($dj->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('dj-profiles', 'public');
        }

        $dj->update($data);

        if ($request->has('categories')) {
            $dj->categories()->sync($request->categories);
        }

        return redirect()->route('dj.dashboard')->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        $dj = DJ::where('user_id', Auth::id())->findOrFail($id);

        if ($dj->profile_image) {
            Storage::disk('public')->delete($dj->profile_image);
        }

        $dj->delete();

        return redirect()->route('dj.dashboard')->with('success', 'DJ profile deleted.');
    }
}
