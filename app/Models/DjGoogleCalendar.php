<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DjGoogleCalendar extends Model
{
    protected $table = 'dj_google_calendars';

    protected $fillable = [
        'dj_id',
        'google_account_email',
        'google_access_token',
        'google_refresh_token',
        'token_expiry',
        'calendar_id',
        'calendar_sync_status',
        'last_synced_at',
        'last_sync_error',
    ];

    protected $hidden = [
        'google_access_token',
        'google_refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'google_access_token' => 'encrypted',
            'google_refresh_token' => 'encrypted',
            'token_expiry' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }

    public function dj(): BelongsTo
    {
        return $this->belongsTo(DJ::class, 'dj_id');
    }

    public function isConnected(): bool
    {
        return $this->calendar_sync_status === 'connected'
            && ! empty($this->google_refresh_token);
    }

    public function markError(string $message): void
    {
        $this->update([
            'calendar_sync_status' => 'error',
            'last_sync_error' => $message,
        ]);
    }

    public function markConnected(?string $email = null): void
    {
        $data = [
            'calendar_sync_status' => 'connected',
            'last_sync_error' => null,
            'last_synced_at' => now(),
        ];

        if ($email) {
            $data['google_account_email'] = $email;
        }

        $this->update($data);
    }
}
