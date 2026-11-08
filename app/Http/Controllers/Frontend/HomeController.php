<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Blog;
use App\Models\Counter;
use App\Models\Faq;
use App\Models\HomepageCarousel;
use App\Models\Partner;
use App\Models\Rental\Building;
use App\Models\Rental\RentalUnit;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBuildings = Building::with('owner')
            ->withCount([
                'units',
                'units as vacant_units_count' => function ($q) {
                    $q->where('is_vacant', true);
                }
            ])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $vacantUnits = RentalUnit::with('building')
            ->where('is_vacant', true)
            ->where('status', 'vacant')
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.home', [
            'setting' => Setting::first(),
            'about' => About::latest()->first(),
            'heroSlides' => HomepageCarousel::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'counters' => Counter::where('status', true)->orderBy('id')->get(),
            'testimonials' => Testimonial::where('status', true)->orderBy('id', 'desc')->get(),
            'services' => Service::where('status', true)->orderBy('sort_order')->get(),
            'featuredBuildings' => $featuredBuildings,
            'vacantUnits' => $vacantUnits,
            'totalBuildings' => Building::count(),
            'vacantUnitsCount' => RentalUnit::where('is_vacant', true)->count(),
            'blogs' => Blog::where('is_published', true)->latest()->take(3)->get(),
            'teamMembers' => TeamMember::where('status', true)->orderBy('sort_order')->take(4)->get(),
            'faqs' => Faq::where('status', true)->orderBy('order')->orderBy('id')->get(),
            'partners' => Partner::where('status', true)->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
