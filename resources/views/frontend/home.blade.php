@extends('frontend.layouts.app')

@section('title', $setting->meta_title ?? 'RentBD – Smart QR Based Rental Platform | বাসা ও দোকান ভাড়া')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
    <style>
        /* Hero Slider */
        .hero-slider {
            position: relative;
            overflow: hidden;
            background: #0f172a;
            min-height: 580px;
        }

        @media (min-width: 992px) {
            .hero-slider {
                min-height: 640px;
            }
        }

        .hero-swiper {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .hero-swiper .swiper-slide {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            background: #0f172a;
            min-height: 580px;
        }

        @media (min-width: 992px) {
            .hero-swiper .swiper-slide {
                min-height: 640px;
            }
        }

        .hero-slide-bg {
            position: absolute;
            inset: -5%;
            background-size: cover;
            background-position: center;
            transform: scale(1);
            filter: brightness(0.45);
        }

        .hero-swiper .swiper-slide-active .hero-slide-bg {
            animation: heroZoom 8s ease-out forwards;
        }

        @keyframes heroZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.70) 55%, rgba(13, 110, 253, 0.35) 100%);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            color: #fff;
            padding-top: 4.5rem;
            padding-bottom: 4rem;
        }

        .hero-swiper-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .hero-swiper-btn:hover {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        .hero-swiper-prev { left: 20px; }
        .hero-swiper-next { right: 20px; }

        .category-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.25s ease;
            background: #ffffff;
        }

        .category-card:hover {
            border-color: #0d6efd;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.1);
        }

        .unit-card {
            border: 1px solid #e9ecef;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .unit-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #0d6efd;
        }

        .step-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .counter-box {
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #eef2f6;
            padding: 24px;
            transition: transform 0.2s ease;
        }

        .counter-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@section('content')

    {{-- 1. HERO CAROUSEL --}}
    <section class="hero-slider">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @php
                    $slides = $heroSlides->isNotEmpty() ? $heroSlides : collect([
                        (object)[
                            'title' => 'স্মার্ট কিউআর কোডে রিয়েল-টাইম বাসা ও দোকান ভাড়া',
                            'subtitle' => 'গেটের কিউআর কোড স্ক্যান করেই জানুন কোন ফ্ল্যাট খালি আছে। AI সার্চ, লাইভ ভ্যাকেন্সি ম্যাপ ও ডিজিটাল চুক্তির মাধ্যমে দ্রুত ও নিরাপদ ভাড়া সেবা।',
                            'image' => null,
                        ],
                        (object)[
                            'title' => 'গুগল ও লাইভ ভ্যাকেন্সি ম্যাপ - টাঙ্গাইল সদর',
                            'subtitle' => 'সবুজ ও লাল মার্কারের মাধ্যমে শহরের সমস্ত খালি বাসা, দোকান ও অফিস এক নজরে ম্যাপে খুঁজুন। অযথা রাস্তায় না ঘুরে সরাসরি আবেদন করুন।',
                            'image' => null,
                        ],
                        (object)[
                            'title' => 'যাচাইকৃত ভাড়াটিয়া ও ডিজিটাল ভাড়া চুক্তিপত্র',
                            'subtitle' => 'NID ও সেলফি ভেরিফিকেশনের মাধ্যমে বিশ্বস্ত ও সুরক্ষিত ভাড়া পরিবেশ। আইনিভাবে প্রস্তুত ই-চুক্তিপত্র ও রেন্ট ম্যানেজমেন্ট।',
                            'image' => null,
                        ],
                    ]);
                @endphp

                @foreach($slides as $slide)
                    <div class="swiper-slide">
                        <div class="hero-slide-bg" style="background-image: url('{{ $slide->image ? asset('storage/' . $slide->image) : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1600&q=80' }}');"></div>
                        <div class="hero-overlay"></div>
                        <div class="container hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <span class="badge bg-primary px-3 py-2 rounded-pill fs-6 mb-3 fw-bold d-inline-flex align-items-center shadow-sm">
                                        <i class="bi bi-upc-scan me-2"></i> RentBD Smart QR Based Rental Platform
                                    </span>
                                    <h1 class="display-4 fw-bold mb-3 text-white leading-tight">
                                        {{ $slide->title }}
                                    </h1>
                                    <p class="lead mb-4 text-white-50 fs-5" style="max-width: 680px;">
                                        {{ $slide->subtitle }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('rentals.index') }}" class="btn btn-primary btn-lg fw-bold px-4 shadow">
                                            <i class="bi bi-search me-1"></i> খালি বাসা খুঁজুন
                                        </a>
                                        <a href="{{ route('rentals.map') }}" class="btn btn-outline-light btn-lg fw-bold px-4 text-white shadow-sm" style="border: 2px solid rgba(255, 255, 255, 0.85); background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(4px);">
                                            <i class="bi bi-map-fill me-2 text-warning"></i> লাইভ ভ্যাকেন্সি ম্যাপ
                                        </a>
                                        <a href="{{ route('owner.dashboard') }}" class="btn btn-light btn-lg fw-bold px-4 text-dark shadow-sm">
                                            <i class="bi bi-building me-1 text-primary"></i> বাড়িওয়ালা পোর্টাল
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="hero-swiper-btn hero-swiper-prev" type="button" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="hero-swiper-btn hero-swiper-next" type="button" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </section>

    {{-- 2. NATURAL LANGUAGE AI SMART SEARCH BAR (FLOATING) --}}
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            <div class="card border-0 shadow-lg rounded-4 p-4" style="margin-top: -55px; position: relative; z-index: 20; background: #ffffff;">
                <form action="{{ route('rentals.index') }}" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-8">
                            <label class="form-label fw-bold text-dark small mb-1">
                                <i class="bi bi-stars text-primary me-1"></i> ন্যাচারাল ল্যাঙ্গুয়েজ AI স্মার্ট সার্চ:
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0 text-primary">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="ai_query" class="form-control border-0 bg-light fs-6" 
                                    placeholder="উদাহরণ: 'টাঙ্গাইল সদরে ১৫ হাজার টাকায় ৩ বেডরুম ফ্ল্যাট' অথবা 'আকুর টাকুর পাড়ায় দোকান'..." 
                                    value="{{ request('ai_query') }}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-bold text-dark small mb-1">ক্যাটেগরি</label>
                            <select name="category" class="form-select form-select-lg bg-light border-0 fs-6">
                                <option value="">সকল ক্যাটেগরি</option>
                                <option value="flat">ফ্ল্যাট (Flat)</option>
                                <option value="house">বাসা (House)</option>
                                <option value="shop">দোকান (Shop)</option>
                                <option value="garage">গ্যারেজ (Garage)</option>
                                <option value="office">অফিস (Office)</option>
                                <option value="mess">মেস / হোস্টেল</option>
                            </select>
                        </div>
                        <div class="col-lg-2 d-grid">
                            <label class="form-label d-none d-lg-block small mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                <i class="bi bi-search me-1"></i> সার্চ করুন
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Quick Tag Chips --}}
                <div class="d-flex flex-wrap gap-2 align-items-center mt-3 pt-3 border-top">
                    <span class="small text-muted fw-semibold"><i class="bi bi-lightning-fill text-warning me-1"></i>জনপ্রিয় অনুসন্ধান:</span>
                    <a href="{{ route('rentals.index', ['ai_query' => 'টাঙ্গাইল সদর']) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">টাঙ্গাইল সদর</a>
                    <a href="{{ route('rentals.index', ['ai_query' => 'আকুর টাকুর পাড়া']) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">আকুর টাকুর পাড়া</a>
                    <a href="{{ route('rentals.index', ['ai_query' => '৩ বেডরুম ফ্ল্যাট']) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">৩ বেডরুম ফ্ল্যাট</a>
                    <a href="{{ route('rentals.index', ['ai_query' => 'দোকান']) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">বাণিজ্যিক দোকান</a>
                    <a href="{{ route('rentals.index', ['ai_query' => 'গ্যারেজ']) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">গ্যারেজ স্পেস</a>
                    <a href="{{ route('rentals.index', ['ai_query' => 'মেস']) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">মেস / হোস্টেল</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. RENTAL CATEGORIES (RESIDENTIAL, COMMERCIAL, OTHER RENTALS) --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="bi bi-grid-fill me-1"></i> Rental Categories
                </span>
                <h2 class="display-6 fw-bold text-dark mb-2">আপনার পছন্দের ভাড়ার ক্যাটেগরি বেছে নিন</h2>
                <p class="text-secondary mx-auto" style="max-width: 650px;">
                    RentBD-তে আবাসিক বাসা, বাণিজ্যিক স্পেস এবং পার্কিং/গ্যারেজ সহ সব ধরনের ভাড়ার নির্ভরযোগ্য তালিকা পান।
                </p>
            </div>

            <div class="row g-4">
                {{-- 1. Residential --}}
                <div class="col-lg-4 col-md-6">
                    <div class="category-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-circle bg-primary-subtle text-primary">
                                <i class="bi bi-house-door-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Residential (আবাসিক)</h5>
                                <small class="text-muted">ফ্যামিলি ও ব্যাচেলর বাসস্থান</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4 d-flex flex-column gap-2">
                            <li><a href="{{ route('rentals.index', ['category' => 'flat']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-primary me-2"></i>ফ্ল্যাট (Family Flats)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'house']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-primary me-2"></i>সম্পূর্ণ বাসা (Independent Houses)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'sublet']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-primary me-2"></i>সাবলেট (Sublet Rooms)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'mess']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-primary me-2"></i>মেস ও হোস্টেল (Mess &amp; Hostel)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                        </ul>
                        <a href="{{ route('rentals.index', ['category_type' => 'residential']) }}" class="btn btn-outline-primary btn-sm w-100 fw-bold">
                            সকল আবাসিক ইউনিট →
                        </a>
                    </div>
                </div>

                {{-- 2. Commercial --}}
                <div class="col-lg-4 col-md-6">
                    <div class="category-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-circle bg-success-subtle text-success">
                                <i class="bi bi-shop-window"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Commercial (বাণিজ্যিক)</h5>
                                <small class="text-muted">দোকান, শোরুম ও অফিস স্পেস</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4 d-flex flex-column gap-2">
                            <li><a href="{{ route('rentals.index', ['category' => 'shop']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-success me-2"></i>দোকান (Retail Shops)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'office']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-success me-2"></i>অফিস স্পেস (Commercial Office)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'showroom']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-success me-2"></i>শোরুম (Showroom)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'warehouse']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-success me-2"></i>গুদাম ও ফ্যাক্টরি (Warehouse/Factory)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                        </ul>
                        <a href="{{ route('rentals.index', ['category_type' => 'commercial']) }}" class="btn btn-outline-success btn-sm w-100 fw-bold">
                            সকল বাণিজ্যিক স্পেস →
                        </a>
                    </div>
                </div>

                {{-- 3. Other Rentals --}}
                <div class="col-lg-4 col-md-6">
                    <div class="category-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-circle bg-warning-subtle text-warning">
                                <i class="bi bi-car-front-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Other Rentals (অন্যান্য)</h5>
                                <small class="text-muted">গ্যারেজ, পার্কিং ও ছাদ ভাড়া</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4 d-flex flex-column gap-2">
                            <li><a href="{{ route('rentals.index', ['category' => 'garage']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-warning me-2"></i>গ্যারেজ স্পেস (Garage)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'parking']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-warning me-2"></i>কার পার্কিং (Dedicated Parking)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'roof']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-warning me-2"></i>ছাদ ভাড়া (Rooftop Lease)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                            <li><a href="{{ route('rentals.index', ['category' => 'land']) }}" class="text-decoration-none text-secondary d-flex justify-content-between align-items-center"><span><i class="bi bi-check2 text-warning me-2"></i>জমি লিজ (Land Lease)</span> <i class="bi bi-chevron-right small text-muted"></i></a></li>
                        </ul>
                        <a href="{{ route('rentals.index', ['category_type' => 'other']) }}" class="btn btn-outline-warning btn-sm w-100 fw-bold text-dark">
                            সকল অন্যান্য ভাড়া →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. REAL-TIME AVAILABLE UNITS (LIVE IN TANGAIL SADAR) --}}
    <section class="py-5 bg-light border-top border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge bg-success text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 0.85rem;">
                        <i class="bi bi-broadcast me-1"></i> Real-Time Vacancy
                    </span>
                    <h2 class="display-6 fw-bold text-dark mb-0">বর্তমানে খালি ইউনিটসমূহ (টাঙ্গাইল সদর)</h2>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('rentals.index') }}" class="btn btn-outline-primary fw-bold">
                        সবগুলো দেখুন ({{ $vacantUnitsCount }}টি খালি) →
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($vacantUnits as $unit)
                    <div class="col-lg-4 col-md-6">
                        <div class="unit-card h-100 d-flex flex-column">
                            <div class="position-relative">
                                @php
                                    $photo = !empty($unit->photos) && is_array($unit->photos) ? $unit->photos[0] : null;
                                @endphp
                                <div style="height: 200px; background: url('{{ $photo ? asset('storage/' . $photo) : 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80' }}') center/cover no-repeat;">
                                </div>
                                <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle-fill me-1"></i> খালি আছে (Vacant)
                                </span>
                                <span class="badge {{ $unit->category_badge_class }} position-absolute bottom-0 start-0 m-3 px-3 py-1 rounded-pill shadow-sm d-inline-flex align-items-center gap-1 fw-bold">
                                    <i class="{{ $unit->category_icon }}"></i> {{ $unit->category_label }}
                                </span>
                            </div>

                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-dark font-monospace">{{ $unit->unit_id }}</span>
                                    <span class="text-muted small">ফ্লোর: {{ $unit->floor_level ?: ($unit->floor ?: 'Ground') }}</span>
                                </div>

                                <h5 class="fw-bold text-dark mb-1">
                                    {{ $unit->unit_number }} ({{ $unit->building?->name_bn ?: $unit->building?->name }})
                                </h5>
                                <p class="text-secondary small mb-3">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    {{ $unit->building?->area }}, {{ $unit->building?->thana_upazila ?: ($unit->building?->thana ?: 'টাঙ্গাইল সদর') }}
                                </p>

                                <div class="d-flex flex-wrap gap-2 mb-3 text-secondary small">
                                    @if($unit->bedrooms)
                                        <span><i class="bi bi-door-closed me-1 text-primary"></i>{{ $unit->bedrooms }} বেড</span>
                                    @endif
                                    @if($unit->bathrooms)
                                        <span><i class="bi bi-droplet me-1 text-info"></i>{{ $unit->bathrooms }} বাথ</span>
                                    @endif
                                    @if($unit->size_sqft)
                                        <span><i class="bi bi-aspect-ratio me-1 text-success"></i>{{ $unit->size_sqft }} sqft</span>
                                    @endif
                                    @if($unit->gas_type)
                                        <span><i class="bi bi-fire me-1 text-warning"></i>{{ ucfirst($unit->gas_type) }}</span>
                                    @endif
                                </div>

                                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-muted d-block">ভাড়া (প্রতি মাস)</small>
                                        <h4 class="fw-bold text-primary mb-0">৳{{ number_format($unit->monthly_rent ?: $unit->rent) }}</h4>
                                    </div>
                                    <a href="{{ route('rentals.show', $unit->unit_id) }}" class="btn btn-primary btn-sm fw-bold px-3">
                                        বিস্তারিত ও আবেদন →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-building-check text-muted display-4 d-block mb-3"></i>
                        <h5 class="fw-bold text-dark">বর্তমানে সব ইউনিট বুকড রয়েছে</h5>
                        <p class="text-secondary">নতুন খালি ইউনিটের আপডেট পেতে নিয়মিত চোখ রাখুন অথবা বাড়িওয়ালা পোর্টালে আপনার ভবন যোগ করুন।</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 5. HOW RENTBD WORKS (HOW IT WORKS 4 STEPS) --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="bi bi-gear-wide-connected me-1"></i> How It Works
                </span>
                <h2 class="display-6 fw-bold text-dark mb-2">RentBD কীভাবে কাজ করে?</h2>
                <p class="text-secondary mx-auto" style="max-width: 650px;">
                    ভাড়া খোঁজা থেকে শুরু করে বাসা নির্বাচন ও ডিজিটাল চুক্তি—সবকিছু মাত্র ৪টি সহজ ধাপে।
                </p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="p-3">
                        <div class="step-circle bg-primary text-white mb-3 shadow">
                            ১
                        </div>
                        <h5 class="fw-bold text-dark mb-2">গেটের কিউআর স্ক্যান</h5>
                        <p class="text-secondary small">
                            ভবনের গেটে লাগানো RentBD কিউআর পোস্টারটি আপনার সাধারণ ফোন ক্যামেরা দিয়ে স্ক্যান করুন।
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="p-3">
                        <div class="step-circle bg-success text-white mb-3 shadow">
                            ২
                        </div>
                        <h5 class="fw-bold text-dark mb-2">লাইভ তথ্য ও ছবি</h5>
                        <p class="text-secondary small">
                            তাৎক্ষণিক দেখুন কোন ফ্ল্যাট বা দোকানটি বর্তমানে খালি আছে, তার ভাড়া, ছবি ও সুবিধাসমূহ।
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="p-3">
                        <div class="step-circle bg-warning text-dark mb-3 shadow">
                            ৩
                        </div>
                        <h5 class="fw-bold text-dark mb-2">সরাসরি যোগাযোগ ও আবেদন</h5>
                        <p class="text-secondary small">
                            এক ক্লিকেই মালিকের সাথে কথা বলুন অথবা ১-ক্লিক ভাড়ার আবেদন ফর্ম সাবমিট করুন।
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="p-3">
                        <div class="step-circle bg-info text-white mb-3 shadow">
                            ৪
                        </div>
                        <h5 class="fw-bold text-dark mb-2">ডিজিটাল ভাড়া চুক্তি</h5>
                        <p class="text-secondary small">
                            NID ভেরিফিকেশনের মাধ্যমে পেপারলেস স্ট্যান্ডার্ড ডিজিটাল চুক্তিপত্র প্রস্তুত ও ঘরে বসেই স্বাক্ষর।
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. UNIQUE PROPERTY ID SYSTEM & POSTER HIGHLIGHT --}}
    <section class="py-5 bg-dark text-white position-relative overflow-hidden">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                        <i class="bi bi-upc me-1"></i> Unique Property ID System
                    </span>
                    <h2 class="display-6 fw-bold mb-3 text-white">
                        প্রতিটি ভবনের জন্য স্থায়ী ডিজিটাল পরিচয় ও প্রিন্টযোগ্য গেট পোস্টার
                    </h2>
                    <p class="text-white-50 lead fs-6 mb-4">
                        RentBD-এর প্রতিটি ভবন ও ইউনিটের রয়েছে একটি ইউনিক পার্মানেন্ট আইডি (যেমন: <code class="text-warning">BD-TNG-000101</code>)। বাড়িওয়ালারা তাদের ড্যাশবোর্ড থেকে সহজেই ডাউনলোড ও প্রিন্ট করতে পারেন A4/A5 সাইজের প্রিমিয়াম গেট পোস্টার।
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px);">
                                <div class="text-warning fw-bold font-monospace mb-1 fs-5">BD-TNG-000101</div>
                                <div class="small text-white fw-medium">বিল্ডিং আইডি (স্বপ্ননীড় টাওয়ার)</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px);">
                                <div class="text-warning fw-bold font-monospace mb-1 fs-5">BD-TNG-000101-F3B</div>
                                <div class="small text-white fw-medium">ফ্ল্যাট ৩বি ইউনিক ইউনিট আইডি</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3">
                        <a href="{{ route('qr.building.poster', 'BD-TNG-000101') }}" target="_blank" class="btn btn-warning btn-lg fw-bold text-dark text-nowrap shadow-sm px-3 px-sm-4">
                            <i class="bi bi-printer me-1"></i> গেট পোস্টার ডেমো দেখুন
                        </a>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-light btn-lg fw-bold text-nowrap px-3 px-sm-4 text-white shadow-sm" style="border: 2px solid rgba(255, 255, 255, 0.85); background: rgba(255, 255, 255, 0.1);">
                            <i class="bi bi-qr-code me-2 text-warning"></i> আমার ভবনের কিউআর তৈরি করুন
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="bg-white p-4 rounded-4 shadow-lg text-dark d-inline-block text-start border border-4 border-primary" style="max-width: 320px;">
                        <div class="text-center border-bottom pb-2 mb-3">
                            <span class="badge bg-danger text-uppercase px-3 py-1 mb-1 fw-bold fs-6">টু-লেট • TO-LET</span>
                            <div class="fw-bold small text-muted">RentBD Smart QR</div>
                        </div>
                        <div class="text-center py-2">
                            {!! (new \App\Services\QrCodeService())->generateSvg(url('/b/BD-TNG-000101'), 180) !!}
                            <div class="small text-primary fw-bold mt-2">স্ক্যান করে খালি ফ্ল্যাট দেখুন</div>
                        </div>
                        <div class="text-center bg-light p-2 rounded mt-2 border">
                            <div class="small text-muted">Property ID</div>
                            <div class="fw-bold font-monospace text-dark">BD-TNG-000101</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. INTERACTIVE VACANCY MAP SECTION --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-4 p-lg-5">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold mb-2">
                                <i class="bi bi-map-fill me-1"></i> Live Vacancy Map
                            </span>
                            <h2 class="display-6 fw-bold text-dark mb-3">
                                টাঙ্গাইল সদর সহ শহরের লাইভ ভ্যাকেন্সি ম্যাপ
                            </h2>
                            <p class="text-secondary lead fs-6 mb-4">
                                শহরের কোন গলিতে বা কোন মোড়ে খালি ফ্ল্যাট বা দোকান আছে তা ম্যাপে এক নজরে দেখুন। রিয়েল-টাইম কালার কোডেড মার্কার:
                            </p>
                            <div class="d-flex flex-column gap-2 mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success rounded-circle p-2"> </span>
                                    <span class="fw-semibold text-dark">সবুজ মার্কার:</span>
                                    <span class="text-secondary small">ভবনে অন্তত একটি ইউনিট খালি আছে (ভাড়া হবে)</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-danger rounded-circle p-2"> </span>
                                    <span class="fw-semibold text-dark">লাল মার্কার:</span>
                                    <span class="text-secondary small">বর্তমানে সব ইউনিট বুকড</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-circle p-2"> </span>
                                    <span class="fw-semibold text-dark">নীল মার্কার:</span>
                                    <span class="text-secondary small">ফিচার্ড / প্রিমিয়াম ভাড়ার সম্পত্তি</span>
                                </div>
                            </div>
                            <a href="{{ route('rentals.map') }}" class="btn btn-danger btn-lg fw-bold px-4 shadow-sm">
                                <i class="bi bi-map-fill me-1"></i> ফুলস্ক্রিন ভ্যাকেন্সি ম্যাপ খুলুন →
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('rentals.map') }}" class="d-block position-relative rounded-4 overflow-hidden shadow border text-decoration-none">
                                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=800&q=80" alt="Map View" class="img-fluid w-100" style="max-height: 340px; object-fit: cover;">
                                <div class="position-absolute top-50 start-50 translate-middle text-center bg-dark bg-opacity-75 text-white p-3 rounded-4 backdrop-blur">
                                    <i class="bi bi-geo-alt-fill text-danger display-5 d-block mb-1"></i>
                                    <div class="fw-bold">ইন্টারেক্টিভ ম্যাপ ভিউ খুলুন</div>
                                    <small class="text-light opacity-75">টাঙ্গাইল সদর ও পার্শ্ববর্তী এলাকা</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. RENTBD CORE RENTAL SERVICES --}}
    @if($services->isNotEmpty())
        <section class="py-5 bg-white" id="servicesSection">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="bi bi-shield-check me-1"></i> Our Services
                    </span>
                    <h2 class="display-6 fw-bold text-dark mb-2">RentBD রেন্টাল সেবাসমূহ</h2>
                    <p class="text-secondary mx-auto" style="max-width: 650px;">
                        মালিক ও ভাড়াটিয়া উভয়ের জন্য নিরাপদ ও আধুনিক প্রোপটেক সমাধান।
                    </p>
                </div>

                <div class="row g-4">
                    @foreach($services as $srv)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 hover-shadow transition" style="background: #f8fafc; border: 1px solid #eef2f6 !important;">
                                <div class="display-6 text-primary mb-3">
                                    <i class="bi {{ $srv->icon ?: 'bi-check-circle-fill' }}"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">{{ $srv->title }}</h5>
                                <p class="text-secondary small mb-3 flex-grow-1">
                                    {{ $srv->short_description ?: Str::limit(strip_tags($srv->description), 100) }}
                                </p>
                                @if($srv->show_price && $srv->price)
                                    <div class="fw-bold text-primary mb-3">ফি: {{ $srv->price_range ?: '৳' . number_format($srv->price) }}</div>
                                @endif
                                <a href="{{ route('services.show', $srv->slug) }}" class="text-primary fw-bold small text-decoration-none">
                                    বিস্তারিত দেখুন →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 9. LIVE COUNTERS --}}
    @if($counters->isNotEmpty())
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row g-4">
                    @foreach($counters as $counter)
                        <div class="col-lg-3 col-sm-6">
                            <div class="counter-box text-center">
                                <div class="display-5 text-primary mb-2">
                                    <i class="bi {{ $counter->icon ?: 'bi-building' }}"></i>
                                </div>
                                <h2 class="fw-bold text-dark mb-1">{{ number_format($counter->value) }}+</h2>
                                <p class="text-secondary small mb-0 fw-semibold">{{ $counter->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 10. ABOUT RENTBD SECTION --}}
    @if($about)
        <section class="py-5 bg-white" id="aboutSection">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80" alt="RentBD About" class="img-fluid rounded-4 shadow w-100">
                            <div class="position-absolute bottom-0 end-0 bg-primary text-white p-3 rounded-4 shadow m-3 text-center">
                                <h4 class="fw-bold mb-0">১০০%</h4>
                                <small>ডিজিটাল রেন্টাল</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold mb-2">
                            <i class="bi bi-info-circle me-1"></i> আমাদের পরিচিতি
                        </span>
                        <h2 class="display-6 fw-bold text-dark mb-3">
                            {{ $about->title ?: 'RentBD সম্পর্কে জানুন' }}
                        </h2>
                        <div class="text-secondary lead fs-6 mb-4 leading-relaxed">
                            {!! $about->page_details ?: '<p>RentBD বাংলাদেশের ভাড়া ব্যবস্থাকে ডিজিটাল ও স্বচ্ছ করতে নিয়ে এসেছে Smart QR Code, Permanent Property ID এবং Verified Ecosystem।</p>' !!}
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-bullseye text-primary me-2"></i>আমাদের লক্ষ্য</div>
                                    <div class="small text-muted">{{ $about->mission ?: 'প্রতিটি বাড়িকে একটি স্থায়ী ডিজিটাল পরিচয় প্রদান করা।' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-eye text-primary me-2"></i>আমাদের ভিশন</div>
                                    <div class="small text-muted">{{ $about->vision ?: 'দেশের ১ নম্বর পেপারলেস রেন্টাল প্ল্যাটফর্ম হওয়া।' }}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('about') }}" class="btn btn-outline-primary fw-bold px-4">
                            আরও জানুন →
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 11. TESTIMONIALS --}}
    @if($testimonials->isNotEmpty())
        <section class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="bi bi-chat-quote me-1"></i> Testimonials
                    </span>
                    <h2 class="display-6 fw-bold text-dark mb-2">ব্যবহারকারীদের অভিজ্ঞতা</h2>
                    <p class="text-secondary mx-auto" style="max-width: 650px;">
                        টাঙ্গাইল সদরের বাড়ির মালিক ও ভাড়াটিয়ারা RentBD সম্পর্কে কী বলছেন।
                    </p>
                </div>

                <div class="row g-4">
                    @foreach($testimonials as $t)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                                <div class="text-warning mb-3">
                                    @for($i = 0; $i < ($t->rating ?: 5); $i++)
                                        <i class="bi bi-star-fill"></i>
                                    @endfor
                                </div>
                                <p class="text-secondary small mb-4 flex-grow-1 leading-relaxed">
                                    "{{ $t->message }}"
                                </p>
                                <div class="d-flex align-items-center gap-3 pt-3 border-top">
                                    <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                        {{ mb_substr($t->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $t->name }}</h6>
                                        <small class="text-muted">{{ $t->designation }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 12. FAQS --}}
    @if($faqs->isNotEmpty())
        <section class="py-5 bg-white" id="faqSection">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="bi bi-question-circle me-1"></i> FAQs
                    </span>
                    <h2 class="display-6 fw-bold text-dark mb-2">সাধারণ প্রশ্নোত্তর</h2>
                    <p class="text-secondary mx-auto" style="max-width: 650px;">
                        RentBD কিউআর কোড, প্রপার্টি আইডি ও ভাড়া সংক্রান্ত সাধারণ জিজ্ঞাসাসমূহ।
                    </p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="accordion accordion-flush shadow-sm rounded-4 border overflow-hidden" id="faqAccordion">
                            @foreach($faqs as $idx => $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $idx }}">
                                        <button class="accordion-button {{ $idx > 0 ? 'collapsed' : '' }} fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $idx }}" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $idx }}" class="accordion-collapse collapse {{ $idx === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-secondary small leading-relaxed">
                                            {{ $faq->answer }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 13. STRATEGIC PARTNERS --}}
    @if($partners->isNotEmpty())
        <section class="py-4 bg-light border-top partners-infinity-section">
            <div class="container text-center">
                <small class="text-muted text-uppercase fw-bold letter-spacing-1 d-block mb-3">আমাদের পার্টনার ও সহযোগী সংস্থা</small>
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-4">
                    @foreach($partners as $partner)
                        <div class="p-2 px-3 bg-white rounded-3 border shadow-sm">
                            <span class="fw-bold text-dark small"><i class="bi bi-patch-check-fill text-primary me-1"></i>{{ $partner->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 14. LANDLORD CTA --}}
    <section class="py-5 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-3 text-white">
                {{ $setting->cta_title ?? 'আপনার বাড়ি বা বাণিজ্যিক ভবনকে দিন ডিজিটাল পরিচয়' }}
            </h2>
            <p class="lead text-white-50 mx-auto mb-4" style="max-width: 650px;">
                {{ $setting->cta_text ?? 'আজই আপনার ভবনে RentBD Smart QR পোস্টার লাগিয়ে খালি ফ্ল্যাট ও দোকানের রিয়েল-টাইম তথ্য দিন এবং যাচাইকৃত ভাড়াটিয়া পান।' }}
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ !empty($setting->cta_button_link) ? $setting->cta_button_link : route('owner.buildings.create') }}" class="btn btn-warning btn-lg fw-bold text-dark px-4 shadow">
                    <i class="bi bi-plus-circle me-1"></i> {{ !empty($setting->cta_button_text) ? $setting->cta_button_text : 'ভবন যোগ করুন ও কিউআর পোস্টার নিন' }}
                </a>
                <a href="{{ route('rentals.index') }}" class="btn btn-outline-light btn-lg fw-bold px-4 text-white" style="border: 2px solid rgba(255, 255, 255, 0.85); background: rgba(255, 255, 255, 0.1);">
                    <i class="bi bi-search me-1"></i> খালি বাসা খুঁজুন
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper !== 'undefined') {
                new Swiper('.hero-swiper', {
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.hero-swiper-next',
                        prevEl: '.hero-swiper-prev',
                    },
                });
            }
        });
    </script>
@endpush
