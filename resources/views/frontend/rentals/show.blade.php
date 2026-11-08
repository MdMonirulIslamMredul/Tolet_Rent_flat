@extends('frontend.layouts.app')

@section('title', $unit->title . ' - ' . $unit->unit_id . ' | RentBD')

@section('content')
<div class="py-4 bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" class="text-decoration-none">ভাড়া তালিকা</a></li>
                <li class="breadcrumb-item"><a href="{{ route('qr.building.show', $building->building_id) }}" class="text-decoration-none">{{ $building->building_id }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $unit->unit_id }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Left Column: Unit Details -->
            <div class="col-lg-8">
                <!-- Main Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="position-relative">
                        @if($unit->thumbnail)
                            <img src="{{ asset($unit->thumbnail) }}" class="w-100 object-fit-cover" style="max-height: 420px;" alt="{{ $unit->title }}">
                        @else
                            <div class="bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="height: 320px;">
                                <i class="bi bi-building display-1"></i>
                            </div>
                        @endif

                        <!-- Floating Badges -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                            @if($unit->is_vacant)
                                <span class="badge bg-success px-3 py-2 fs-6 rounded-pill shadow">
                                    <i class="bi bi-check-circle-fill me-1"></i> খালি (ভাড়া হবে)
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill shadow">
                                    <i class="bi bi-lock-fill me-1"></i> বর্তমানে বুকড
                                </span>
                            @endif
                            <span class="badge bg-dark bg-opacity-75 px-3 py-2 fs-6 rounded-pill shadow">
                                {{ $unit->unit_id }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-baseline mb-2">
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fs-6">
                                {{ $unit->category_name_bn }}
                            </span>
                            <div class="text-end">
                                <span class="text-muted small">মাসিক ভাড়া:</span>
                                <h2 class="fw-bold text-primary mb-0">{{ number_format($unit->monthly_rent) }} ৳</h2>
                            </div>
                        </div>

                        <h3 class="fw-bold text-dark mb-2">{{ $unit->title }}</h3>

                        <p class="text-secondary mb-4">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                            <strong>{{ $building->name_bn ?: $building->name }}</strong> — {{ $building->address }}
                        </p>

                        <!-- Key Spec Grid -->
                        <div class="row g-3 p-3 bg-light rounded-4 mb-4 text-center">
                            @if($unit->bedrooms)
                                <div class="col-4 col-md-2 border-end">
                                    <div class="small text-muted">বেডরুম</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $unit->bedrooms }}</div>
                                </div>
                            @endif
                            @if($unit->bathrooms)
                                <div class="col-4 col-md-2 border-end">
                                    <div class="small text-muted">বাথরুম</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $unit->bathrooms }}</div>
                                </div>
                            @endif
                            @if($unit->balconies)
                                <div class="col-4 col-md-2 border-end">
                                    <div class="small text-muted">বারান্দা</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $unit->balconies }}</div>
                                </div>
                            @endif
                            @if($unit->size_sqft)
                                <div class="col-4 col-md-2 border-end">
                                    <div class="small text-muted">আয়তন</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $unit->size_sqft }} sqft</div>
                                </div>
                            @endif
                            @if($unit->floor_level)
                                <div class="col-4 col-md-2 border-end">
                                    <div class="small text-muted">ফ্লোর</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $unit->floor_level }}</div>
                                </div>
                            @endif
                            @if($unit->facing)
                                <div class="col-4 col-md-2">
                                    <div class="small text-muted">মুখমুখী</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $unit->facing }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Financial & Utility Breakdown -->
                        <h5 class="fw-bold text-dark mb-3">ভাড়া ও ইউটিলিটি বিবরণ</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="bg-light w-25">সার্ভিস চার্জ</th>
                                        <td>{{ $unit->service_charge > 0 ? number_format($unit->service_charge) . ' ৳' : 'ভাড়ার অন্তর্ভুক্ত' }}</td>
                                        <th class="bg-light w-25">অগ্রিম জামানত (Deposit)</th>
                                        <td>{{ $unit->security_deposit > 0 ? number_format($unit->security_deposit) . ' ৳' : 'আলোচনা সাপেক্ষ' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">গ্যাস ব্যবস্থা</th>
                                        <td>{{ $unit->gas_type === 'pipeline' ? 'তিতাস পাইপলাইন গ্যাস' : ($unit->gas_type === 'cylinder' ? 'সিলিন্ডার গ্যাস' : 'গ্যাস নেই') }}</td>
                                        <th class="bg-light">বিদ্যুৎ মিটার</th>
                                        <td>{{ $unit->electricity_type === 'prepaid' ? 'প্রিপেইড স্মার্ট মিটার' : 'পোস্টপেইড বিল' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">পানি সরবরাহ</th>
                                        <td>{{ $unit->water_supply ?: '২৪ ঘণ্টা নিরবচ্ছিন্ন পানি' }}</td>
                                        <th class="bg-light">পছন্দের ভাড়াটিয়া</th>
                                        <td>{{ $unit->preferred_tenant === 'family' ? 'ছোট/মাঝারি ফ্যামিলি' : ($unit->preferred_tenant === 'bachelor' ? 'ব্যাচেলর' : 'যেকোনো উপযুক্ত ভাড়াটিয়া') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($unit->notes)
                            <h5 class="fw-bold text-dark mb-2">বাড়ির মালিকের বিশেষ নোট</h5>
                            <p class="text-secondary leading-relaxed p-3 bg-light rounded-3">{{ $unit->notes }}</p>
                        @endif
                    </div>
                </div>

                <!-- Building Info Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-building text-primary me-2"></i> ভবনের সাধারণ সুবিধাসমূহ
                        </h5>
                        <a href="{{ route('qr.building.show', $building->building_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-upc-scan me-1"></i> ভবনের কিউআর পেজ
                        </a>
                    </div>

                    <div class="row g-2 mb-3">
                        @if(is_array($building->amenities))
                            @foreach($building->amenities as $facility)
                                <div class="col-md-4 col-sm-6">
                                    <div class="p-2 bg-light rounded border d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="small fw-semibold">{{ $facility }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Actions & Landlord -->
            <div class="col-lg-4">
                <!-- Quick Apply Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 sticky-top" style="top: 20px; z-index: 10;">
                    <h5 class="fw-bold text-dark mb-3">ভাড়ার জন্য আবেদন করুন</h5>

                    @if($unit->is_vacant)
                        <div class="alert alert-success d-flex align-items-center p-3 rounded-3 mb-3">
                            <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                            <div>ইউনিটটি বর্তমানে খালি আছে এবং সরাসরি আবেদন গ্রহণ করা হচ্ছে।</div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('rentals.apply.create', $unit->unit_id) }}" class="btn btn-primary btn-lg fw-bold shadow">
                                <i class="bi bi-pencil-square me-1"></i> এখন আবেদন করুন (Apply Now)
                            </a>
                        </div>
                    @else
                        <div class="alert alert-danger p-3 rounded-3 mb-3">
                            <i class="bi bi-x-circle-fill me-1"></i> এই ইউনিটটি বর্তমানে ভাড়া হয়ে গেছে।
                        </div>
                    @endif

                    <hr>

                    <!-- Landlord Contact Details -->
                    <h6 class="fw-bold text-dark mb-2">বাড়ির মালিক / কেয়ারটেকার</h6>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-circle fs-3 text-secondary me-2"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $building->contact_person ?: $building->owner?->name }}</h6>
                                @if($building->owner?->isVerified())
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                                        <i class="bi bi-patch-check-fill"></i> যাচাইকৃত মালিক (Verified)
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($building->contact_phone)
                            <div class="d-grid gap-2 mt-3">
                                <a href="tel:{{ $building->contact_phone }}" class="btn btn-outline-success fw-bold">
                                    <i class="bi bi-telephone-fill me-1"></i> কল করুন ({{ $building->contact_phone }})
                                </a>
                                @if($building->contact_whatsapp)
                                    <a href="https://wa.me/88{{ preg_replace('/[^0-9]/', '', $building->contact_whatsapp) }}" target="_blank" class="btn btn-success fw-bold">
                                        <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপ মেসেজ
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Bookmark / Save Button -->
                    @auth
                        <form action="{{ route('tenant.saved.toggle', $unit->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-heart me-1"></i> পছন্দের তালিকায় রাখুন
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

