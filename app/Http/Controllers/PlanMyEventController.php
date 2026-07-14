<?php

namespace App\Http\Controllers;

use App\Mail\PlanMyEventSubmittedMail;
use App\Models\PlanMyEventRequest;
use App\Support\BookDjValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PlanMyEventController extends Controller
{
    public function create()
    {
        return $this->showForm();
    }

    public function showForm()
    {
        return view('plan-my-event.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), BookDjValidation::rules());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        [$budgetMin, $budgetMax] = BookDjValidation::parseBudgetRange($validated['budget_range']);

        $user = Auth::user();

        $planMyEventRequest = PlanMyEventRequest::create([
            'user_id' => $user?->id,
            'client_name' => $validated['client_name'],
            'city' => $validated['city'],
            'zipcode' => $validated['zipcode'],
            'event_date' => $validated['event_date'],
            'event_type' => $validated['event_type'],
            'venue_type' => $validated['venue_type'],
            'venue_type_other' => $validated['venue_type_other'] ?? null,
            'venue_name' => $validated['venue_name'],
            'venue_address' => $validated['venue_address'],
            'budget_range' => $validated['budget_range'],
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'dj_name' => $validated['dj_name'] ?? null,
            'use_near_me' => $request->boolean('use_near_me'),
            'rush_guarantee' => $request->boolean('rush_guarantee'),
            'email' => $user?->email,
            'phone' => $user?->phone,
            'status' => 'new',
        ]);

        $notifyEmail = config('services.plan_my_event.email')
            ?: config('mail.plan_my_event_address');

        if ($notifyEmail) {
            Mail::to($notifyEmail)->send(new PlanMyEventSubmittedMail($planMyEventRequest));
        }

        return redirect()
            ->route('plan-my-event')
            ->with('success', 'Thank you! Your event details have been submitted. We will get back to you soon.');
    }
}
