<?php

namespace App\Http\Controllers;

use App\Models\DJ;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DJController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $djs = DJ::with('user', 'categories')
            ->available()
            ->verified()
            ->orderBy('rating', 'desc')
            ->paginate(12);

        return view('browse', compact('djs'));
    }

    public function create()
    {
        if (!Auth::user()->isDJ()) {
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
            'experience_years' => 'required|integer|min:0',
            'specialties' => 'nullable|array',
            'genres' => 'nullable|array',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'equipment' => 'nullable|string',
            'categories' => 'nullable|array',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('dj-profiles', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['specialties'] = $request->specialties ?? [];
        $data['genres'] = $request->genres ?? [];

        $dj = DJ::create($data);

        if ($request->has('categories')) {
            $dj->categories()->sync($request->categories);
        }

        return redirect()->route('dj.show', $dj->id)->with('success', 'DJ profile created successfully!');
    }

    public function show($id)
    {
        $dj = DJ::with('user', 'categories', 'reviews.user')
            ->findOrFail($id);

        return view('dj.show', compact('dj'));
    }

    public function edit($id)
    {
        $dj = DJ::findOrFail($id);

        if (Auth::id() !== $dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        return view('dj.edit', compact('dj', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $dj = DJ::findOrFail($id);

        if (Auth::id() !== $dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'stage_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'hourly_rate' => 'required|numeric|min:0',
            'experience_years' => 'required|integer|min:0',
            'specialties' => 'nullable|array',
            'genres' => 'nullable|array',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'equipment' => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_image')) {
            if ($dj->profile_image) {
                Storage::disk('public')->delete($dj->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('dj-profiles', 'public');
        }

        $data['specialties'] = $request->specialties ?? [];
        $data['genres'] = $request->genres ?? [];

        $dj->update($data);

        if ($request->has('categories')) {
            $dj->categories()->sync($request->categories);
        }

        return redirect()->route('dj.show', $dj->id)->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        $dj = DJ::findOrFail($id);

        if (Auth::id() !== $dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($dj->profile_image) {
            Storage::disk('public')->delete($dj->profile_image);
        }

        $dj->delete();

        return redirect()->route('browse')->with('success', 'DJ profile deleted successfully.');
    }
}
