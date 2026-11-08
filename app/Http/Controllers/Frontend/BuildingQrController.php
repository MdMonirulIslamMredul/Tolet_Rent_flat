<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rental\Building;
use App\Models\Rental\QrScanLog;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingQrController extends Controller
{
    /**
     * Show building real-time vacancy page when QR code is scanned
     */
    public function show(Request $request, string $building_id)
    {
        $building = Building::where('building_id', $building_id)
            ->with(['owner', 'units' => function ($q) {
                $q->orderBy('is_vacant', 'desc')->orderBy('floor_level');
            }])
            ->firstOrFail();

        // Log QR scan for analytics
        try {
            QrScanLog::create([
                'building_id' => $building->id,
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'scanned_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently continue if log fails
        }

        $vacantUnits = $building->units->where('is_vacant', true);
        $occupiedUnits = $building->units->where('is_vacant', false);
        $qrImageUrl = QrCodeService::getQrImageUrl($building->qr_url, 300);

        return view('frontend.qr.building', compact('building', 'vacantUnits', 'occupiedUnits', 'qrImageUrl'));
    }

    /**
     * Printable A4/A5 Gate Poster for Building Entrance
     */
    public function poster(string $building_id)
    {
        $building = Building::where('building_id', $building_id)->with('owner')->firstOrFail();
        $qrImageUrl = QrCodeService::getQrImageUrl($building->qr_url, 400);

        return view('owner.buildings.poster', compact('building', 'qrImageUrl'));
    }
}

