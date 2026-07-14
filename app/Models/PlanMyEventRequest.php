<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanMyEventRequest extends Model
{
    protected $fillable = [
        'user_id',
        'client_name',
        'city',
        'zipcode',
        'event_date',
        'event_type',
        'venue_type',
        'venue_type_other',
        'venue_name',
        'venue_address',
        'budget_range',
        'budget_min',
        'budget_max',
        'dj_name',
        'use_near_me',
        'rush_guarantee',
        'email',
        'phone',
        'special_requests',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'use_near_me' => 'boolean',
            'rush_guarantee' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getVenueTypeLabelAttribute(): string
    {
        if ($this->venue_type === 'other') {
            return $this->venue_type_other ?: 'Other';
        }

        return ucfirst($this->venue_type);
    }
}
