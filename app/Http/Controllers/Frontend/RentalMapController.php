<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rental\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentalMapController extends Controller
{
    /**
     * Map View page
     */
    public function index(Request $request)
    {
        $defaultDistrict = $request->input('district', 'Tangail');
        $defaultThana = $request->input('thana', 'Tangail Sadar');

        return view('frontend.rentals.map', compact('defaultDistrict', 'defaultThana'));
    }

    /**
     * API for fetching map markers with real-time vacancy status
     */
    public function markers(Request $request): JsonResponse
    {
        $query = Building::query()
            ->with(['units'])
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('district')) {
            $query->where('district', $request->input('district'));
        }

        if ($request->filled('thana')) {
            $query->where('thana_upazila', $request->input('thana'));
        }

        if ($request->filled('area')) {
            $query->where('area', 'like', '%' . $request->input('area') . '%');
        }

        if ($request->boolean('vacant_only')) {
            $query->where('vacant_units_count', '>', 0);
        }

        $buildings = $query->get()->map(function (Building $building) {
            $hasVacant = $building->vacant_units_count > 0;
            $markerColor = $building->is_featured ? 'blue' : ($hasVacant ? 'green' : 'red');

            return [
                'id' => $building->id,
                'building_id' => $building->building_id,
                'name' => $building->name,
                'name_bn' => $building->name_bn,
                'area' => $building->area,
                'thana' => $building->thana_upazila,
                'district' => $building->district,
                'address' => $building->address,
                'lat' => (float) $building->latitude,
                'lng' => (float) $building->longitude,
                'total_units' => $building->total_units,
                'vacant_units' => $building->vacant_units_count,
                'rent_range' => $building->rent_range,
                'is_featured' => $building->is_featured,
                'marker_color' => $markerColor, // green, red, blue
                'qr_url' => $building->qr_url,
                'thumbnail' => $building->thumbnail ? asset($building->thumbnail) : asset('images/default-building.jpg'),
                'contact_phone' => $building->contact_phone,
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $buildings->count(),
            'center' => [
                'lat' => 24.2513, // Tangail Sadar center
                'lng' => 89.9167,
                'zoom' => 14,
            ],
            'buildings' => $buildings,
        ]);
    }
}

