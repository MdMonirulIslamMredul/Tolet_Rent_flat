<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rental\RentalApplication;
use App\Models\Rental\RentalUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalApplicationController extends Controller
{
    /**
     * Show application form for a specific unit
     */
    public function create(string $unit_id)
    {
        $unit = RentalUnit::where('unit_id', $unit_id)
            ->with(['building.owner'])
            ->firstOrFail();

        if (!$unit->is_vacant) {
            return redirect()->route('rentals.show', $unit_id)
                ->with('error', 'দুঃখিত, এই ইউনিটটি বর্তমানে খালি নেই।');
        }

        $user = Auth::user();

        return view('frontend.rentals.apply', compact('unit', 'user'));
    }

    /**
     * Store new rental application
     */
    public function store(Request $request, string $unit_id)
    {
        $unit = RentalUnit::where('unit_id', $unit_id)->with('building')->firstOrFail();

        $validated = $request->validate([
            'proposed_move_in_date' => 'required|date|after_or_equal:today',
            'family_members_count' => 'nullable|integer|min:1|max:20',
            'profession' => 'nullable|string|max:100',
            'monthly_income_range' => 'nullable|string|max:50',
            'current_address' => 'nullable|string|max:255',
            'message_to_owner' => 'nullable|string|max:1000',
        ]);

        $nextNum = RentalApplication::count() + 1;
        $appNo = sprintf('APP-%s-%04d', date('Y'), $nextNum);

        $application = RentalApplication::create([
            'application_no' => $appNo,
            'unit_id' => $unit->id,
            'building_id' => $unit->building_id,
            'tenant_id' => Auth::id(),
            'owner_id' => $unit->building->owner_id,
            'status' => 'pending',
            'proposed_move_in_date' => $validated['proposed_move_in_date'],
            'family_members_count' => $validated['family_members_count'] ?? null,
            'profession' => $validated['profession'] ?? null,
            'monthly_income_range' => $validated['monthly_income_range'] ?? null,
            'current_address' => $validated['current_address'] ?? null,
            'message_to_owner' => $validated['message_to_owner'] ?? null,
        ]);

        return redirect()->route('tenant.dashboard')
            ->with('success', "আপনার আবেদন সফলভাবে জমা হয়েছে! আবেদন নম্বর: {$appNo}। বাড়িওয়ালা পর্যালোচনা করে আপনার সাথে যোগাযোগ করবেন।");
    }
}

