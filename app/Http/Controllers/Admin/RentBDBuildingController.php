<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental\Building;
use Illuminate\Http\Request;

class RentBDBuildingController extends Controller
{
    public function index(Request $request)
    {
        $query = Building::with(['owner', 'units'])->withCount('units');

        if ($request->filled('district')) {
            $query->where('district', $request->input('district'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $buildings = $query->latest()->paginate(15);

        return view('admin.rentbd.buildings.index', compact('buildings'));
    }

    public function toggleFeatured(Building $building)
    {
        $building->is_featured = !$building->is_featured;
        $building->save();

        return back()->with('success', 'বিল্ডিং ফিচার্ড স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে।');
    }

    public function updateStatus(Request $request, Building $building)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,pending_approval',
        ]);

        $building->update($validated);

        return back()->with('success', 'বিল্ডিং স্ট্যাটাস আপডেট করা হয়েছে।');
    }
}

