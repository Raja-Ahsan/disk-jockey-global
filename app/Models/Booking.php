<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'dj_id',
        'event_id',
        'booking_date',
        'start_time',
        'end_time',
        'venue_address',
        'city',
        'state',
        'zipcode',
        'total_amount',
        'deposit_amount',
        'payment_status',
        'booking_status',
        'special_requests',
        'cancellation_reason',
        'confirmed_at',
        'completed_at',
        'stripe_payment_intent_id',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'total_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dj(): BelongsTo
    {
        return $this->belongsTo(DJ::class, 'dj_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('booking_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('booking_status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('booking_status', 'completed');
    }
}
