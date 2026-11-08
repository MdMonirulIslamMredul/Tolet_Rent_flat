<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rental\Building;
use App\Models\Rental\RentalUnit;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;

class RentalDirectoryController extends Controller
{
    /**
     * Browse rental listings with filters and AI Smart Search
     */
    public function index(Request $request)
    {
        $query = RentalUnit::query()
            ->with(['building.owner'])
            ->whereHas('building', function ($q) {
                $q->where('status', 'active');
            });

        $parsedCriteria = null;

        // Check if AI Natural Language search is triggered
        if ($request->filled('ai_query')) {
            $parsedCriteria = SmartSearchService::parse($request->input('ai_query'));

            if (!empty($parsedCriteria['district'])) {
                $query->whereHas('building', function ($q) use ($parsedCriteria) {
                    $q->where('district', $parsedCriteria['district']);
                });
            }

            if (!empty($parsedCriteria['thana_upazila'])) {
                $query->whereHas('building', function ($q) use ($parsedCriteria) {
                    $q->where('thana_upazila', $parsedCriteria['thana_upazila']);
                });
            }

            if (!empty($parsedCriteria['area'])) {
                $query->whereHas('building', function ($q) use ($parsedCriteria) {
                    $q->where('area', 'like', '%' . $parsedCriteria['area'] . '%');
                });
            }

            if (!empty($parsedCriteria['category'])) {
                $query->where('category', $parsedCriteria['category']);
            }

            if (!empty($parsedCriteria['category_type'])) {
                $query->where('category_type', $parsedCriteria['category_type']);
            }

            if (!empty($parsedCriteria['bedrooms'])) {
                $query->where('bedrooms', '>=', $parsedCriteria['bedrooms']);
            }

            if (!empty($parsedCriteria['min_rent'])) {
                $query->where('monthly_rent', '>=', $parsedCriteria['min_rent']);
            }

            if (!empty($parsedCriteria['max_rent'])) {
                $query->where('monthly_rent', '<=', $parsedCriteria['max_rent']);
            }

            if (!empty($parsedCriteria['preferred_tenant'])) {
                $query->where(function ($q) use ($parsedCriteria) {
                    $q->where('preferred_tenant', $parsedCriteria['preferred_tenant'])
                      ->orWhere('preferred_tenant', 'any');
                });
            }
        } else {
            // Standard filters
            if ($request->filled('category')) {
                $query->where('category', $request->input('category'));
            }

            if ($request->filled('category_type')) {
                $query->where('category_type', $request->input('category_type'));
            }

            if ($request->filled('area')) {
                $query->whereHas('building', function ($q) use ($request) {
                    $q->where('area', 'like', '%' . $request->input('area') . '%');
                });
            }

            if ($request->filled('district')) {
                $query->whereHas('building', function ($q) use ($request) {
                    $q->where('district', $request->input('district'));
                });
            }

            if ($request->filled('bedrooms')) {
                $query->where('bedrooms', '>=', $request->input('bedrooms'));
            }

            if ($request->filled('min_rent')) {
                $query->where('monthly_rent', '>=', $request->input('min_rent'));
            }

            if ($request->filled('max_rent')) {
                $query->where('monthly_rent', '<=', $request->input('max_rent'));
            }

            if ($request->boolean('vacant_only', true)) {
                $query->where('is_vacant', true);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('monthly_rent', 'asc'),
            'price_high' => $query->orderBy('monthly_rent', 'desc'),
            'size' => $query->orderBy('size_sqft', 'desc'),
            default => $query->orderByDesc('is_vacant')->orderByDesc('id'),
        };

        $units = $query->paginate(12)->withQueryString();

        // Stats for quick filters (Tangail Sadar MVP focus)
        $totalVacant = RentalUnit::where('is_vacant', true)->count();
        $tangailAreas = Building::where('district', 'Tangail')
            ->distinct()
            ->pluck('area');

        return view('frontend.rentals.index', compact('units', 'totalVacant', 'tangailAreas', 'parsedCriteria'));
    }

    /**
     * Show single rental unit detail
     */
    public function show(string $unit_id)
    {
        $unit = RentalUnit::where('unit_id', $unit_id)
            ->with(['building.owner', 'building.units' => function ($q) use ($unit_id) {
                $q->where('unit_id', '!=', $unit_id)->where('is_vacant', true)->limit(3);
            }])
            ->firstOrFail();

        $building = $unit->building;
        $otherVacantUnitsInBuilding = $building->units;

        return view('frontend.rentals.show', compact('unit', 'building', 'otherVacantUnitsInBuilding'));
    }
}

