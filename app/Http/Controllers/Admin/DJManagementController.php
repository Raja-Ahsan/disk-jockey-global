<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DJ;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DJManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = DJ::with('user', 'categories');

        if ($request->filled('search')) {
            $query->where('stage_name', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === '1');
        }

        $djs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.djs.index', compact('djs'));
    }

    public function show($id)
    {
        $dj = DJ::with('user', 'categories', 'bookings', 'reviews.user')->findOrFail($id);
        return view('admin.djs.show', compact('dj'));
    }

    public function edit($id)
    {
        $dj = DJ::with('categories')->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.djs.edit', compact('dj', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $dj = DJ::findOrFail($id);

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
            'is_verified' => 'boolean',
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

        return redirect()->route('admin.djs.index')->with('success', 'DJ updated successfully!');
    }

    public function verify($id)
    {
        $dj = DJ::findOrFail($id);
        $dj->update(['is_verified' => true]);

        return redirect()->back()->with('success', 'DJ verified successfully!');
    }

    public function unverify($id)
    {
        $dj = DJ::findOrFail($id);
        $dj->update(['is_verified' => false]);

        return redirect()->back()->with('success', 'DJ verification removed.');
    }

    public function destroy($id)
    {
        $dj = DJ::findOrFail($id);

        if ($dj->profile_image) {
            Storage::disk('public')->delete($dj->profile_image);
        }

        $dj->delete();

        return redirect()->route('admin.djs.index')->with('success', 'DJ deleted successfully.');
    }
}
