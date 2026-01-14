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
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
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

    // Accessors
    public function getLocationAttribute(): string
    {
        return "{$this->city}, {$this->state}";
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->city, $this->state, $this->zipcode]);
        return implode(', ', $parts);
    }

    public function getFormattedStartTimeAttribute(): string
    {
        if ($this->event_date && $this->start_time) {
            return \Carbon\Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->start_time)->format('M d, Y g:i A');
        }
        return 'N/A';
    }

    public function getFormattedEndTimeAttribute(): string
    {
        if ($this->event_date && $this->end_time) {
            return \Carbon\Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->end_time)->format('M d, Y g:i A');
        }
        return 'N/A';
    }
}
