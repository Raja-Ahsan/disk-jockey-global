<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanMyEventRequest;
use Illuminate\Http\Request;

class PlanMyEventManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->isAdmin()) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = PlanMyEventRequest::with('user')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('admin.plan-my-event.index', compact('requests'));
    }

    public function show($id)
    {
        $planMyEventRequest = PlanMyEventRequest::with('user')->findOrFail($id);

        return view('admin.plan-my-event.show', compact('planMyEventRequest'));
    }

    public function update(Request $request, $id)
    {
        $planMyEventRequest = PlanMyEventRequest::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,contacted,closed,cancelled',
            'notes' => 'nullable|string|max:5000',
        ]);

        $planMyEventRequest->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Plan My Event request updated successfully!');
    }

    public function destroy($id)
    {
        $planMyEventRequest = PlanMyEventRequest::findOrFail($id);
        $planMyEventRequest->delete();

        return redirect()
            ->route('admin.plan-my-event.index')
            ->with('success', 'Plan My Event request deleted successfully.');
    }
}
