<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BookingRequest extends Model
{
    protected $fillable = [
        'user_id',
        'dj_id',
        'booking_id',
        'client_name',
        'event_type',
        'event_date',
        'venue_type',
        'venue_type_other',
        'venue_name',
        'venue_address',
        'city',
        'state',
        'zipcode',
        'budget_min',
        'budget_max',
        'rush_guarantee',
        'rush_fee',
        'booking_fee',
        'dj_amount',
        'total_amount',
        'special_requests',
        'status',
        'dj_response',
        'response_token',
        'expires_at',
        'dj_responded_at',
        'paid_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'rush_fee' => 'decimal:2',
            'booking_fee' => 'decimal:2',
            'dj_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'rush_guarantee' => 'boolean',
            'expires_at' => 'datetime',
            'dj_responded_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public static function booted(): void
    {
        static::creating(function (BookingRequest $request) {
            if (empty($request->response_token)) {
                $request->response_token = Str::random(64);
            }
            if (empty($request->expires_at)) {
                $hours = $request->rush_guarantee ? 6 : 48;
                $request->expires_at = now()->addHours($hours);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dj(): BelongsTo
    {
        return $this->belongsTo(DJ::class, 'dj_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast() && $this->dj_response === 'pending';
    }

    public function respondUrl(string $answer): string
    {
        return route('booking-requests.respond', [
            'token' => $this->response_token,
            'answer' => $answer,
        ]);
    }
}
