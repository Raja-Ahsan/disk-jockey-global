<?php

namespace App\Http\Controllers\DJ;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleCalendarController extends Controller
{
    public function __construct(
        protected GoogleCalendarService $googleCalendar
    ) {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (! $user || ! $user->isDJ() || ! $user->dj) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function connect()
    {
        if (! $this->googleCalendar->isConfigured()) {
            return redirect()->route('dj.dashboard.profile')
                ->with('error', 'Google Calendar is not configured. Please contact the administrator.');
        }

        return redirect()->away($this->googleCalendar->getAuthorizationUrl(Auth::user()->dj));
    }

    public function callback(Request $request)
    {
        if ($request->filled('error')) {
            return redirect()->route('dj.dashboard.profile')
                ->with('error', 'Google Calendar authorization was cancelled.');
        }

        $request->validate([
            'code' => 'required|string',
            'state' => 'required|string',
        ]);

        try {
            $this->googleCalendar->handleCallback($request->code, $request->state);

            return redirect()->route('dj.dashboard.profile')
                ->with('success', 'Google Calendar connected successfully.');
        } catch (\Throwable $e) {
            Log::error('Google Calendar OAuth callback failed', ['error' => $e->getMessage()]);

            return redirect()->route('dj.dashboard.profile')
                ->with('error', 'Failed to connect Google Calendar. Please try again.');
        }
    }

    public function disconnect()
    {
        $this->googleCalendar->disconnect(Auth::user()->dj);

        return redirect()->route('dj.dashboard.profile')
            ->with('success', 'Google Calendar disconnected.');
    }

    public function resync()
    {
        $ok = $this->googleCalendar->resync(Auth::user()->dj);

        return redirect()->route('dj.dashboard.profile')
            ->with(
                $ok ? 'success' : 'error',
                $ok ? 'Google Calendar synced successfully.' : 'Google Calendar sync failed. Please reconnect.'
            );
    }
}
