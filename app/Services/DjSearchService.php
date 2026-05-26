<?php

namespace App\Services;

use App\Models\DJ;
use Illuminate\Database\Eloquent\Builder;

class DjSearchService
{
    public function __construct(
        protected ZipDistanceService $zipDistance
    ) {}

    public function baseQuery(): Builder
    {
        return DJ::query()
            ->with('user', 'categories')
            ->available()
            ->verified();
    }

    public function applyBudgetFilter(Builder $query, ?float $budgetMin, ?float $budgetMax): Builder
    {
        if ($budgetMin !== null && $budgetMin > 0) {
            $query->where('hourly_rate', '>=', $budgetMin);
        }

        if ($budgetMax !== null && $budgetMax > 0) {
            $query->where('hourly_rate', '<=', $budgetMax);
        }

        return $query;
    }

    public function applyNearMeFilter(Builder $query, string $customerZip): Builder
    {
        $allDjs = (clone $query)->get();
        $ids = $this->zipDistance->filterDjsByRadius($allDjs, $customerZip);

        if (empty($ids)) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('id', $ids);
    }

    public function findByStageName(string $name): ?DJ
    {
        return $this->baseQuery()
            ->where('stage_name', 'like', '%' . trim($name) . '%')
            ->orderBy('rating', 'desc')
            ->first();
    }

    public function search(array $params): Builder
    {
        $query = $this->baseQuery();

        if (! empty($params['search_by_name']) && ! empty($params['dj_name'])) {
            $query->where('stage_name', 'like', '%' . trim($params['dj_name']) . '%');

            return $query->orderBy('rating', 'desc');
        }

        $budgetMin = isset($params['budget_min']) ? (float) $params['budget_min'] : null;
        $budgetMax = isset($params['budget_max']) ? (float) $params['budget_max'] : null;
        $this->applyBudgetFilter($query, $budgetMin, $budgetMax);

        if (! empty($params['use_near_me']) && ! empty($params['zipcode'])) {
            return $this->applyNearMeFilter($query, $params['zipcode'])
                ->orderBy('rating', 'desc');
        }

        return $query->orderBy('rating', 'desc');
    }
}
