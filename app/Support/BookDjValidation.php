<?php

namespace App\Support;

class BookDjValidation
{
    /**
     * Shared validation rules for the Book A DJ / Plan My Event forms.
     */
    public static function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipcode' => 'required|string|max:20',
            'event_date' => 'required|date|after_or_equal:today',
            'event_type' => 'required|string|max:255',
            'venue_type' => 'required|in:club,hall,house,other',
            'venue_type_other' => 'required_if:venue_type,other|nullable|string|max:255',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:500',
            'budget_range' => 'required|string',
            'use_near_me' => 'nullable|in:0,1',
            'search_by_name' => 'nullable|in:0,1',
            'dj_name' => 'nullable|string|max:255',
            'rush_guarantee' => 'nullable|boolean',
        ];
    }

    /**
     * Parse a budget range string (e.g. "100-500") into [min, max].
     */
    public static function parseBudgetRange(string $range): array
    {
        if (str_contains($range, '-')) {
            [$min, $max] = explode('-', $range, 2);

            return [(float) $min, (float) $max];
        }

        return [100, 25000];
    }
}
