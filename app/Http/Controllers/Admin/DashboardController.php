<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Partner;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Rental\Building;
use App\Models\Rental\DigitalAgreement;
use App\Models\Rental\QrScanLog;
use App\Models\Rental\RentalApplication;
use App\Models\Rental\RentalUnit;
use App\Models\Rental\UserVerification;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. RentBD Platform KPI Metrics
        $totalBuildings = Building::count();
        $totalUnits = RentalUnit::count();
        $vacantUnits = RentalUnit::where('is_vacant', true)->count();
        $occupiedUnits = RentalUnit::where('is_vacant', false)->count();
        $totalApplications = RentalApplication::count();
        $pendingApplications = RentalApplication::where('status', 'pending')->count();
        $pendingVerifications = UserVerification::where('status', 'pending')->count();
        $verifiedUsers = User::where('verification_status', 'verified')->count();
        $totalAgreements = DigitalAgreement::count();
        $totalQrScans = QrScanLog::count();

        // 2. Recent Buildings
        $recentBuildings = Building::with('owner')->withCount([
            'units',
            'units as vacant_units_count' => function ($q) {
                $q->where('is_vacant', true);
            }
        ])->latest()->take(5)->get();

        // 3. Recent Rental Applications
        $recentApplications = RentalApplication::with(['unit.building', 'tenant', 'owner'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Recent NID Verifications
        $recentVerifications = UserVerification::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // 5. Recent Contacts
        $recentContacts = Contact::latest()->take(5)->get();

        // 6. Content Stats
        $contentStats = [
            'services' => Service::count(),
            'blogs' => Blog::count(),
            'team_members' => TeamMember::count(),
            'partners' => Partner::count(),
            'testimonials' => Testimonial::count(),
            'contacts' => Contact::count(),
            'unread_contacts' => Contact::unread()->count(),
        ];

        return view('admin.dashboard', compact(
            'totalBuildings',
            'totalUnits',
            'vacantUnits',
            'occupiedUnits',
            'totalApplications',
            'pendingApplications',
            'pendingVerifications',
            'verifiedUsers',
            'totalAgreements',
            'totalQrScans',
            'recentBuildings',
            'recentApplications',
            'recentVerifications',
            'recentContacts',
            'contentStats'
        ));
    }
}
