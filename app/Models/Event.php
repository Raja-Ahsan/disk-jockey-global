<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_type',
        'event_date',
        'start_time',
        'end_time',
        'venue_name',
        'address',
        'city',
        'state',
        'zipcode',
        'guest_count',
        'requirements',
        'budget_min',
        'budget_max',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'requirements' => 'array',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
