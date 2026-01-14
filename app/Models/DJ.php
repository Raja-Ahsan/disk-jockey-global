<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DJ extends Model
{
    protected $table = 'djs';

    protected $fillable = [
        'user_id',
        'stage_name',
        'bio',
        'profile_image',
        'city',
        'state',
        'zipcode',
        'hourly_rate',
        'experience_years',
        'specialties',
        'genres',
        'phone',
        'website',
        'social_links',
        'equipment',
        'is_verified',
        'is_available',
        'rating',
        'total_reviews',
        'total_bookings',
    ];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
            'genres' => 'array',
            'social_links' => 'array',
            'hourly_rate' => 'decimal:2',
            'rating' => 'decimal:2',
            'is_verified' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_dj', 'dj_id', 'category_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'dj_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'dj_id');
    }

    // Accessors
    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/default-dj.png');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByLocation($query, $city = null, $state = null, $zipcode = null)
    {
        if ($city) {
            $query->where('city', 'like', "%{$city}%");
        }
        if ($state) {
            $query->where('state', $state);
        }
        if ($zipcode) {
            $query->where('zipcode', $zipcode);
        }
        return $query;
    }

    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('hourly_rate', '>=', $min);
        }
        if ($max) {
            $query->where('hourly_rate', '<=', $max);
        }
        return $query;
    }

    public function scopeByGenre($query, $genre)
    {
        return $query->whereJsonContains('genres', $genre);
    }
}
