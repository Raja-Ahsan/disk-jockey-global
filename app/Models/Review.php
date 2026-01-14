<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'dj_id',
        'rating',
        'comment',
        'aspects',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'aspects' => 'array',
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dj(): BelongsTo
    {
        return $this->belongsTo(DJ::class, 'dj_id');
    }
}
