<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ZipDistanceService
{
    private const MAX_MILES = 250;

    public function getCoordinates(string $zipcode): ?array
    {
        $zip = $this->normalizeZip($zipcode);

        if (! $zip) {
            return null;
        }

        return Cache::remember("zip_coords_{$zip}", 86400 * 30, function () use ($zip) {
            try {
                $response = Http::timeout(5)->get("https://api.zippopotam.us/us/{$zip}");

                if (! $response->successful()) {
                    return null;
                }

                $place = $response->json('places.0');

                if (! $place) {
                    return null;
                }

                return [
                    'lat' => (float) $place['latitude'],
                    'lng' => (float) $place['longitude'],
                ];
            } catch (\Throwable) {
                return null;
            }
        });
    }

    public function distanceInMiles(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 3958.8;
        $latFrom = deg2rad($lat1);
        $latTo = deg2rad($lat2);
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function isWithinRadius(string $customerZip, string $djZip, int $maxMiles = self::MAX_MILES): bool
    {
        $customer = $this->getCoordinates($customerZip);
        $dj = $this->getCoordinates($djZip);

        if (! $customer || ! $dj) {
            return $this->fallbackZipMatch($customerZip, $djZip);
        }

        return $this->distanceInMiles(
            $customer['lat'],
            $customer['lng'],
            $dj['lat'],
            $dj['lng']
        ) <= $maxMiles;
    }

    public function filterDjsByRadius(iterable $djs, string $customerZip, int $maxMiles = self::MAX_MILES): array
    {
        $ids = [];

        foreach ($djs as $dj) {
            if ($this->isWithinRadius($customerZip, $dj->zipcode, $maxMiles)) {
                $ids[] = $dj->id;
            }
        }

        return $ids;
    }

    private function fallbackZipMatch(string $customerZip, string $djZip): bool
    {
        $c = $this->normalizeZip($customerZip);
        $d = $this->normalizeZip($djZip);

        if (! $c || ! $d) {
            return false;
        }

        return substr($c, 0, 3) === substr($d, 0, 3);
    }

    private function normalizeZip(string $zipcode): ?string
    {
        $digits = preg_replace('/\D/', '', $zipcode);

        if (strlen($digits) < 5) {
            return null;
        }

        return substr($digits, 0, 5);
    }
}
