@extends('frontend.layouts.app')

@section('title', ($building->name_bn ?: $building->name) . ' - রিয়েল-টাইম ভ্যাকেন্সি ও কিউআর পোর্টাল | RentBD')

@section('content')
<div class="py-4 bg-light">
    <div class="container">
        <!-- Breadcrumb & Top Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">হোম</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" class="text-decoration-none">ভাড়া প্রপার্টি</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $building->building_id }}</li>
                </ol>
            </nav>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('qr.building.poster', $building->building_id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer me-1"></i> গেট পোস্টার প্রিন্ট
                </a>
            </div>
        </div>

        <!-- Building Header Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="row align-items-center g-4">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill">
                                <i class="bi bi-upc-scan me-1"></i> {{ $building->building_id }}
                            </span>
                            @if($building->is_featured)
                                <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill">
                                    <i class="bi bi-star-fill text-warning me-1"></i> ফিচার্ড প্রপার্টি
                                </span>
                            @endif
                            @if($building->owner?->isVerified())
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6 rounded-pill">
                                    <i class="bi bi-patch-check-fill text-success me-1"></i> ভেরিফাইড মালিক
                                </span>
                            @endif
                        </div>

                        <h2 class="fw-bold mb-1 text-dark">{{ $building->name_bn ?: $building->name }}</h2>
                        <h6 class="text-muted mb-3">{{ $building->name }}</h6>

                        <p class="mb-2 text-secondary">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                            <strong>{{ $building->area }}, {{ $building->thana_upazila }}, {{ $building->district }}</strong> — {{ $building->address }}
                        </p>

                        <!-- Real-time Vacancy Status Banner -->
                        <div class="mt-3">
                            @if($building->vacant_units_count > 0)
                                <div class="alert alert-success d-flex align-items-center p-3 rounded-3 mb-0" role="alert">
                                    <div class="display-6 me-3 text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading fw-bold mb-1 text-success">
                                            ভাড়া হবে! {{ $building->vacant_units_count }}টি ইউনিট খালি আছে
                                        </h5>
                                        <p class="mb-0 small text-success-emphasis">
                                            রিয়েল-টাইম আপডেট: মোট {{ $building->total_units }}টি ইউনিটের মধ্যে বর্তমানে {{ $building->vacant_units_count }}টি ইউনিটে সরাসরি আবেদন করা যাবে।
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger d-flex align-items-center p-3 rounded-3 mb-0" role="alert">
                                    <div class="display-6 me-3 text-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading fw-bold mb-1 text-danger">বর্তমানে সম্পূর্ণ বুকড</h5>
                                        <p class="mb-0 small text-danger-emphasis">
                                            এই ভবনের কোনো ইউনিট বর্তমানে খালি নেই। নতুন ইউনিট খালি হওয়া মাত্রই এখানে আপডেট দেখানো হবে।
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Building QR & Quick Action Card -->
                    <div class="col-md-4 text-center">
                        <div class="p-3 bg-light rounded-4 border">
                            <img src="{{ $qrImageUrl }}" alt="QR Code" class="img-fluid rounded-3 shadow-sm mb-2" style="max-height: 180px;">
                            <div class="small fw-bold text-dark mb-1">স্থায়ী কিউআর কোড (Smart QR)</div>
                            <div class="badge bg-secondary mb-3">{{ $building->building_id }}</div>
                            <div class="d-grid gap-2">
                                @if($building->contact_phone)
                                    <a href="tel:{{ $building->contact_phone }}" class="btn btn-success fw-bold">
                                        <i class="bi bi-telephone-fill me-1"></i> সরাসরি কল করুন
                                    </a>
                                @endif
                                @if($building->contact_whatsapp)
                                    <a href="https://wa.me/88{{ preg_replace('/[^0-9]/', '', $building->contact_whatsapp) }}" target="_blank" class="btn btn-outline-success">
                                        <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে কথা বলুন
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills nav-fill bg-white p-2 rounded-4 shadow-sm mb-4" id="buildingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold py-2 rounded-3" id="vacant-tab" data-bs-toggle="pill" data-bs-target="#vacant" type="button" role="tab">
                    <i class="bi bi-door-open-fill text-success me-1"></i> খালি ইউনিটসমূহ ({{ $vacantUnits->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2 rounded-3" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                    <i class="bi bi-building me-1"></i> ভবনের সকল ইউনিট ({{ $building->units->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2 rounded-3" id="amenities-tab" data-bs-toggle="pill" data-bs-target="#amenities" type="button" role="tab">
                    <i class="bi bi-stars text-warning me-1"></i> ভবনের সুবিধাসমূহ
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2 rounded-3" id="location-tab" data-bs-toggle="pill" data-bs-target="#location" type="button" role="tab">
                    <i class="bi bi-map-fill text-danger me-1"></i> লোকেশন ম্যাপ
                </button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="buildingTabContent">
            <!-- 1. Vacant Units Tab -->
            <div class="tab-pane fade show active" id="vacant" role="tabpanel">
                @if($vacantUnits->count() > 0)
                    <div class="row g-4">
                        @foreach($vacantUnits as $unit)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-shadow transition">
                                    <div class="position-absolute top-0 start-0 m-3 z-2">
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i> খালি আছে
                                        </span>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3 z-2">
                                        <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill">
                                            {{ $unit->unit_id }}
                                        </span>
                                    </div>

                                    @if($unit->thumbnail)
                                        <img src="{{ asset($unit->thumbnail) }}" class="card-img-top object-fit-cover" style="height: 200px;" alt="{{ $unit->title }}">
                                    @else
                                        <div class="bg-primary-subtle d-flex align-items-center justify-content-center text-primary" style="height: 200px;">
                                            <i class="bi bi-building display-3"></i>
                                        </div>
                                    @endif

                                    <div class="card-body p-4 d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge {{ $unit->category_badge_class }} px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1 shadow-sm">
                                                <i class="{{ $unit->category_icon }}"></i> {{ $unit->category_label }}
                                            </span>
                                            <span class="text-muted small">
                                                <i class="bi bi-layers me-1"></i> {{ $unit->floor_level }}
                                            </span>
                                        </div>

                                        <h5 class="card-title fw-bold text-dark mb-2">{{ $unit->title }}</h5>
                                        <p class="text-muted small mb-3">{{ $unit->summary_spec }}</p>

                                        <!-- Rent & Spec Badges -->
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-baseline">
                                                <span class="text-muted small">মাসিক ভাড়া:</span>
                                                <span class="fs-4 fw-bold text-primary">{{ number_format($unit->monthly_rent) }} ৳</span>
                                            </div>
                                            @if($unit->service_charge > 0)
                                                <div class="d-flex justify-content-between text-muted small mt-1">
                                                    <span>সার্ভিস চার্জ:</span>
                                                    <span>{{ number_format($unit->service_charge) }} ৳</span>
                                                </div>
                                            @endif
                                            @if($unit->gas_type)
                                                <div class="d-flex justify-content-between text-muted small mt-1">
                                                    <span>গ্যাস ব্যবস্থা:</span>
                                                    <span>{{ $unit->gas_type === 'pipeline' ? 'তিতাস লাইন গ্যাস' : ($unit->gas_type === 'cylinder' ? 'সিলিন্ডার গ্যাস' : 'প্রযোজ্য নয়') }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-auto pt-2 d-flex gap-2">
                                            <a href="{{ route('rentals.show', $unit->unit_id) }}" class="btn btn-outline-primary flex-grow-1">
                                                বিস্তারিত
                                            </a>
                                            <a href="{{ route('rentals.apply.create', $unit->unit_id) }}" class="btn btn-primary flex-grow-1 fw-bold">
                                                আবেদন করুন
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                        <i class="bi bi-house-x display-1 text-muted mb-3"></i>
                        <h4 class="fw-bold text-secondary">বর্তমানে এই ভবনে কোনো ইউনিট খালি নেই</h4>
                        <p class="text-muted">বাড়িওয়ালা যখনই কোনো ফ্ল্যাট বা দোকান খালি হিসেবে চিহ্নিত করবেন, এখানে তাৎক্ষণিক আপডেট দেখতে পাবেন।</p>
                    </div>
                @endif
            </div>

            <!-- 2. All Units Tab -->
            <div class="tab-pane fade" id="all" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ইউনিট আইডি</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>ফ্লোর / নম্বর</th>
                                    <th>সাইজ ও রুম</th>
                                    <th>মাসিক ভাড়া</th>
                                    <th>ভ্যাকেন্সি স্ট্যাটাস</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($building->units as $unit)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $unit->unit_id }}</strong>
                                        </td>
                                        <td>{{ $unit->category_name_bn }}</td>
                                        <td>{{ $unit->floor_level }} ({{ $unit->unit_number }})</td>
                                        <td>{{ $unit->summary_spec }}</td>
                                        <td class="fw-bold">{{ number_format($unit->monthly_rent) }} ৳</td>
                                        <td>
                                            @if($unit->is_vacant)
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i> খালি (ভাড়া হবে)
                                                </span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-lock-fill me-1"></i> বুকড
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit->is_vacant)
                                                <a href="{{ route('rentals.apply.create', $unit->unit_id) }}" class="btn btn-sm btn-primary">আবেদন</a>
                                            @else
                                                <span class="text-muted small">অনুপলব্ধ</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Building Facilities Tab -->
            <div class="tab-pane fade" id="amenities" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold text-dark mb-3">এই ভবনের বিশেষ সুবিধাসমূহ</h5>
                    <div class="row g-3">
                        @if(is_array($building->amenities) && count($building->amenities) > 0)
                            @foreach($building->amenities as $item)
                                <div class="col-md-4 col-sm-6">
                                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center">
                                        <i class="bi bi-check2-circle text-success fs-4 me-2"></i>
                                        <span class="fw-medium text-dark">{{ $item }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">কোনো বিশেষ সুবিধার তালিকা যুক্ত করা হয়নি।</p>
                        @endif
                    </div>

                    @if($building->description)
                        <hr class="my-4">
                        <h6 class="fw-bold text-dark mb-2">ভবনের বিবরণ</h6>
                        <p class="text-secondary leading-relaxed">{{ $building->description }}</p>
                    @endif
                </div>
            </div>

            <!-- 4. Location Map Tab -->
            <div class="tab-pane fade" id="location" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold text-dark mb-3">ভবনের অবস্থান ও যোগাযোগ</h5>
                    <p class="text-muted mb-3">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $building->address }}
                    </p>

                    @if($building->latitude && $building->longitude)
                        <div class="mb-3">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $building->latitude }},{{ $building->longitude }}" target="_blank" class="btn btn-outline-danger">
                                <i class="bi bi-google me-1"></i> গুগল ম্যাপে রুট দেখুন (Open in Google Maps)
                            </a>
                        </div>
                    @endif

                    <div class="p-4 bg-light rounded-4 border">
                        <h6 class="fw-bold text-dark mb-2">যোগাযোগের তথ্য</h6>
                        <p class="mb-1"><strong>যোগাযোগকারী:</strong> {{ $building->contact_person ?: $building->owner?->name }}</p>
                        <p class="mb-1"><strong>মোবাইল নম্বর:</strong> {{ $building->contact_phone ?: '01711-000000' }}</p>
                        @if($building->contact_whatsapp)
                            <p class="mb-0"><strong>হোয়াটসঅ্যাপ:</strong> {{ $building->contact_whatsapp }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

