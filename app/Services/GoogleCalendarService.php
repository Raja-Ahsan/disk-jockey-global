<?php

namespace App\Services;

use App\Models\DJ;
use App\Models\DjGoogleCalendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleCalendarService
{
    public function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    public function getAuthorizationUrl(DJ $dj): string
    {
        $state = Str::random(40);
        session([
            'google_oauth_state' => $state,
            'google_oauth_dj_id' => $dj->id,
        ]);

        $params = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => implode(' ', [
                'https://www.googleapis.com/auth/calendar.readonly',
                'https://www.googleapis.com/auth/userinfo.email',
                'openid',
            ]),
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
            'include_granted_scopes' => 'true',
        ]);

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    public function handleCallback(string $code, string $state): DjGoogleCalendar
    {
        if ($state !== session('google_oauth_state')) {
            throw new \RuntimeException('Invalid Google OAuth state.');
        }

        $djId = session('google_oauth_dj_id');
        if (! $djId) {
            throw new \RuntimeException('Missing DJ context for Google OAuth.');
        }

        $dj = DJ::findOrFail($djId);
        $tokens = $this->exchangeCode($code);
        $email = $this->fetchAccountEmail($tokens['access_token']);

        $calendar = DjGoogleCalendar::updateOrCreate(
            ['dj_id' => $dj->id],
            [
                'google_account_email' => $email,
                'google_access_token' => $tokens['access_token'],
                'google_refresh_token' => $tokens['refresh_token'] ?? optional($dj->googleCalendar)->google_refresh_token,
                'token_expiry' => now()->addSeconds((int) ($tokens['expires_in'] ?? 3600)),
                'calendar_id' => 'primary',
                'calendar_sync_status' => 'connected',
                'last_synced_at' => now(),
                'last_sync_error' => null,
            ]
        );

        session()->forget(['google_oauth_state', 'google_oauth_dj_id']);

        return $calendar;
    }

    public function disconnect(DJ $dj): void
    {
        $calendar = $dj->googleCalendar;
        if (! $calendar) {
            return;
        }

        try {
            if ($calendar->google_access_token) {
                Http::asForm()->post('https://oauth2.googleapis.com/revoke', [
                    'token' => $calendar->google_access_token,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Google token revoke failed', [
                'dj_id' => $dj->id,
                'error' => $e->getMessage(),
            ]);
        }

        $calendar->update([
            'google_account_email' => null,
            'google_access_token' => null,
            'google_refresh_token' => null,
            'token_expiry' => null,
            'calendar_sync_status' => 'disconnected',
            'last_synced_at' => null,
            'last_sync_error' => null,
        ]);
    }

    public function resync(DJ $dj): bool
    {
        $calendar = $dj->googleCalendar;
        if (! $calendar || ! $calendar->google_refresh_token) {
            return false;
        }

        try {
            $calendar->update(['calendar_sync_status' => 'syncing']);
            $this->refreshAccessToken($calendar);
            $email = $this->fetchAccountEmail($calendar->google_access_token);
            $calendar->markConnected($email);

            return true;
        } catch (\Throwable $e) {
            Log::error('Google Calendar resync failed', [
                'dj_id' => $dj->id,
                'error' => $e->getMessage(),
            ]);
            $calendar->markError($e->getMessage());

            return false;
        }
    }

    /**
     * Returns true when the DJ is free for the requested window.
     * If calendar is not connected, returns true (no blocking).
     */
    public function isAvailable(DJ $dj, Carbon $start, Carbon $end): bool
    {
        $calendar = $dj->googleCalendar;
        if (! $calendar || ! $calendar->isConnected()) {
            return true;
        }

        try {
            $accessToken = $this->ensureValidAccessToken($calendar);

            $response = Http::withToken($accessToken)
                ->post('https://www.googleapis.com/calendar/v3/freeBusy', [
                    'timeMin' => $start->copy()->utc()->toIso8601String(),
                    'timeMax' => $end->copy()->utc()->toIso8601String(),
                    'items' => [
                        ['id' => $calendar->calendar_id ?: 'primary'],
                    ],
                ]);

            if (! $response->successful()) {
                Log::error('Google freeBusy failed', [
                    'dj_id' => $dj->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $calendar->markError('FreeBusy API error: HTTP ' . $response->status());

                // Fail open for transient API issues so bookings are not hard-blocked offline
                return true;
            }

            $calendar->update([
                'last_synced_at' => now(),
                'last_sync_error' => null,
                'calendar_sync_status' => 'connected',
            ]);

            $busy = $response->json('calendars.' . ($calendar->calendar_id ?: 'primary') . '.busy', []);

            return empty($busy);
        } catch (\Throwable $e) {
            Log::error('Google availability check failed', [
                'dj_id' => $dj->id,
                'error' => $e->getMessage(),
            ]);
            $calendar->markError($e->getMessage());

            return true;
        }
    }

    public function assertAvailableOrFail(DJ $dj, string $date, string $startTime = '18:00:00', string $endTime = '22:00:00'): void
    {
        $start = Carbon::parse($date . ' ' . $startTime, config('app.timezone'));
        $end = Carbon::parse($date . ' ' . $endTime, config('app.timezone'));

        if ($end->lte($start)) {
            $end->addDay();
        }

        if (! $this->isAvailable($dj, $start, $end)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'event_date' => 'This DJ is unavailable for the selected date. Please choose another available date.',
            ]);
        }
    }

    protected function exchangeCode(string $code): array
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Failed to exchange Google OAuth code.');
        }

        return $response->json();
    }

    protected function ensureValidAccessToken(DjGoogleCalendar $calendar): string
    {
        if ($calendar->token_expiry && $calendar->token_expiry->isFuture() && $calendar->google_access_token) {
            return $calendar->google_access_token;
        }

        return $this->refreshAccessToken($calendar);
    }

    protected function refreshAccessToken(DjGoogleCalendar $calendar): string
    {
        if (! $calendar->google_refresh_token) {
            throw new \RuntimeException('Missing Google refresh token.');
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $calendar->google_refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if (! $response->successful()) {
            $calendar->markError('Token refresh failed: HTTP ' . $response->status());
            throw new \RuntimeException('Failed to refresh Google access token.');
        }

        $data = $response->json();
        $calendar->update([
            'google_access_token' => $data['access_token'],
            'token_expiry' => now()->addSeconds((int) ($data['expires_in'] ?? 3600)),
            'calendar_sync_status' => 'connected',
            'last_sync_error' => null,
        ]);

        return $data['access_token'];
    }

    protected function fetchAccountEmail(string $accessToken): ?string
    {
        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if (! $response->successful()) {
            return null;
        }

        return $response->json('email');
    }
}
