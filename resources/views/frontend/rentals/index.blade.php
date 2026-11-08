@extends('frontend.layouts.app')

@section('title', 'খালি বাসা, ফ্ল্যাট ও দোকান খুঁজুন - রিয়েল-টাইম ভ্যাকেন্সি ও AI সার্চ | RentBD')

@section('content')
<!-- Hero / Search Section -->
<div class="py-5 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-9">
                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill mb-2">
                    <i class="bi bi-upc-scan me-1"></i> RentBD Smart QR & AI Rental Platform
                </span>
                <h1 class="fw-bold display-5 mb-2">রিয়েল-টাইম খালি বাসা, ফ্ল্যাট ও দোকান খুঁজুন</h1>
                <p class="fs-6 opacity-75 mb-4">
                    প্রতিটি বিল্ডিংয়ের সামনে থাকা কিউআর কোড স্ক্যান করে অথবা ন্যাচারাল ল্যাঙ্গুয়েজে লিখে সহজেই কাঙ্ক্ষিত ভাড়া প্রপার্টি খুঁজে নিন।
                </p>

                <!-- AI Smart Search Input Box -->
                <div class="card border-0 shadow-lg rounded-4 p-3 bg-white text-dark text-start">
                    <form action="{{ route('rentals.index') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-0 text-primary fs-4">
                                <i class="bi bi-stars"></i>
                            </span>
                            <input type="text" name="ai_query" class="form-control border-0 fs-6" 
                                placeholder="AI সার্চ: যেমন- 'টাঙ্গাইল সদরে ১৫ হাজার টাকায় ৩ বেডরুম ফ্ল্যাট' বা 'দোকান চাই'..." 
                                value="{{ request('ai_query') }}">
                            <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3">
                                <i class="bi bi-search me-1"></i> AI সার্চ করুন
                            </button>
                        </div>
                    </form>

                    <!-- Quick Suggestions -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2 border-top">
                        <span class="small fw-bold text-muted me-1">জনপ্রিয় সার্চ:</span>
                        <a href="{{ route('rentals.index', ['ai_query' => 'টাঙ্গাইল সদরে ৩ বেডরুম ফ্ল্যাট']) }}" class="badge bg-light text-secondary border text-decoration-none py-2 px-3 rounded-pill">
                            ৩ বেডরুম ফ্ল্যাট
                        </a>
                        <a href="{{ route('rentals.index', ['ai_query' => 'আকুর টাকুর পাড়ায় বাসা']) }}" class="badge bg-light text-secondary border text-decoration-none py-2 px-3 rounded-pill">
                            আকুর টাকুর পাড়া
                        </a>
                        <a href="{{ route('rentals.index', ['ai_query' => 'টাঙ্গাইলে দোকান']) }}" class="badge bg-light text-secondary border text-decoration-none py-2 px-3 rounded-pill">
                            দোকান ও বাণিজ্যিক স্পেস
                        </a>
                        <a href="{{ route('rentals.index', ['ai_query' => 'গ্যারেজ বা পার্কিং']) }}" class="badge bg-light text-secondary border text-decoration-none py-2 px-3 rounded-pill">
                            গ্যারেজ / পার্কিং
                        </a>
                        <a href="{{ route('rentals.index', ['ai_query' => '১০ হাজার টাকার মধ্যে']) }}" class="badge bg-light text-secondary border text-decoration-none py-2 px-3 rounded-pill">
                            ১০,০০০ ৳ এর মধ্যে
                        </a>
                    </div>
                </div>

                @if($parsedCriteria)
                    <div class="alert alert-info mt-3 text-start d-flex align-items-center justify-content-between p-3 rounded-3">
                        <div>
                            <strong><i class="bi bi-robot me-1"></i> AI পার্সিং ফলাফল:</strong>
                            <span class="ms-2">
                                @if(!empty($parsedCriteria['district'])) জেলা: <strong>{{ $parsedCriteria['district'] }}</strong>, @endif
                                @if(!empty($parsedCriteria['area'])) এলাকা: <strong>{{ $parsedCriteria['area'] }}</strong>, @endif
                                @if(!empty($parsedCriteria['category'])) ক্যাটাগরি: <strong>{{ $parsedCriteria['category'] }}</strong>, @endif
                                @if(!empty($parsedCriteria['bedrooms'])) বেডরুম: <strong>{{ $parsedCriteria['bedrooms'] }}+</strong>, @endif
                                @if(!empty($parsedCriteria['max_rent'])) বাজেট: <strong>সর্বোচ্চ {{ number_format($parsedCriteria['max_rent']) }} ৳</strong> @endif
                            </span>
                        </div>
                        <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-outline-dark">ফিল্টার মুছুন</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Main Listing Section -->
<div class="py-5 bg-light">
    <div class="container">
        <!-- Controls Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">উপলব্ধ ভাড়ার তালিকা</h4>
                <p class="text-muted small mb-0">মোট {{ $units->total() }}টি ইউনিটের ফলাফল পাওয়া গেছে (টাঙ্গাইল সদর টার্গেট)</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('rentals.map') }}" class="btn btn-outline-danger fw-bold shadow-sm">
                    <i class="bi bi-map-fill me-1"></i> গুগল / লাইভ ম্যাপে দেখুন
                </a>
            </div>
        </div>

        <!-- Filter Chips Bar -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
            <form action="{{ route('rentals.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3 col-6">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">সকল ক্যাটাগরি</option>
                        <option value="flat" {{ request('category') == 'flat' ? 'selected' : '' }}>ফ্ল্যাট (Flat)</option>
                        <option value="house" {{ request('category') == 'house' ? 'selected' : '' }}>বাসা (House)</option>
                        <option value="shop" {{ request('category') == 'shop' ? 'selected' : '' }}>দোকান (Shop)</option>
                        <option value="office" {{ request('category') == 'office' ? 'selected' : '' }}>অফিস (Office)</option>
                        <option value="garage" {{ request('category') == 'garage' ? 'selected' : '' }}>গ্যারেজ (Garage)</option>
                        <option value="mess" {{ request('category') == 'mess' ? 'selected' : '' }}>মেস (Mess)</option>
                        <option value="sublet" {{ request('category') == 'sublet' ? 'selected' : '' }}>সাবলেট (Sublet)</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <select name="area" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">সকল এলাকা (টাঙ্গাইল)</option>
                        @foreach($tangailAreas as $area)
                            <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="bedrooms" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">বেডরুম</option>
                        <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>১+ বেড</option>
                        <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>২+ বেড</option>
                        <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>৩+ বেড</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="max_rent" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">বাজেট সীমা</option>
                        <option value="10000" {{ request('max_rent') == '10000' ? 'selected' : '' }}>১০,০০০ ৳ পর্যন্ত</option>
                        <option value="15000" {{ request('max_rent') == '15000' ? 'selected' : '' }}>১৫,০০০ ৳ পর্যন্ত</option>
                        <option value="25000" {{ request('max_rent') == '25000' ? 'selected' : '' }}>২৫,০০০ ৳ পর্যন্ত</option>
                    </select>
                </div>
                <div class="col-md-2 col-12 text-end">
                    <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-outline-secondary w-100">রিসেট</a>
                </div>
            </form>
        </div>

        <!-- Property Grid -->
        <div class="row g-4">
            @forelse($units as $unit)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-shadow transition position-relative">
                        <!-- Badges -->
                        <div class="position-absolute top-0 start-0 m-3 z-2">
                            @if($unit->is_vacant)
                                <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> খালি (ভাড়া হবে)
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-lock-fill me-1"></i> বুকড
                                </span>
                            @endif
                        </div>
                        <div class="position-absolute top-0 end-0 m-3 z-2">
                            <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill">
                                {{ $unit->unit_id }}
                            </span>
                        </div>

                        <!-- Image -->
                        @if($unit->thumbnail)
                            <img src="{{ asset($unit->thumbnail) }}" class="card-img-top object-fit-cover" style="height: 220px;" alt="{{ $unit->title }}">
                        @else
                            <div class="bg-primary-subtle d-flex align-items-center justify-content-center text-primary" style="height: 220px;">
                                <i class="bi bi-building display-3"></i>
                            </div>
                        @endif

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge {{ $unit->category_badge_class }} px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="{{ $unit->category_icon }}"></i> {{ $unit->category_label }}
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-geo-alt-fill text-danger"></i> {{ $unit->building?->area }}, {{ $unit->building?->district }}
                                </span>
                            </div>

                            <h5 class="card-title fw-bold text-dark mb-1">{{ $unit->title }}</h5>
                            <p class="text-secondary small mb-3">
                                <strong>{{ $unit->building?->name_bn ?: $unit->building?->name }}</strong>
                            </p>

                            <!-- Specs -->
                            <div class="d-flex flex-wrap gap-2 text-muted small mb-3">
                                @if($unit->bedrooms)
                                    <span class="bg-light p-2 rounded"><i class="bi bi-door-closed text-primary me-1"></i>{{ $unit->bedrooms }} বেড</span>
                                @endif
                                @if($unit->bathrooms)
                                    <span class="bg-light p-2 rounded"><i class="bi bi-droplet text-info me-1"></i>{{ $unit->bathrooms }} বাথ</span>
                                @endif
                                @if($unit->size_sqft)
                                    <span class="bg-light p-2 rounded"><i class="bi bi-aspect-ratio text-warning me-1"></i>{{ $unit->size_sqft }} sqft</span>
                                @endif
                                @if($unit->floor_level)
                                    <span class="bg-light p-2 rounded"><i class="bi bi-layers text-success me-1"></i>{{ $unit->floor_level }}</span>
                                @endif
                            </div>

                            <!-- Rent Box -->
                            <div class="p-3 bg-light rounded-3 mb-3 mt-auto">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <span class="text-muted small">মাসিক ভাড়া:</span>
                                    <span class="fs-4 fw-bold text-primary">{{ number_format($unit->monthly_rent) }} ৳</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('rentals.show', $unit->unit_id) }}" class="btn btn-outline-primary flex-grow-1">
                                    বিস্তারিত
                                </a>
                                @if($unit->is_vacant)
                                    <a href="{{ route('rentals.apply.create', $unit->unit_id) }}" class="btn btn-primary flex-grow-1 fw-bold">
                                        আবেদন করুন
                                    </a>
                                @else
                                    <button class="btn btn-secondary flex-grow-1" disabled>দখলকৃত</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                        <i class="bi bi-search display-2 text-muted mb-3"></i>
                        <h4 class="fw-bold text-secondary">কোনো প্রপার্টি খুঁজে পাওয়া যায়নি</h4>
                        <p class="text-muted mb-3">আপনার সার্চ ফিল্টার পরিবর্তন করে পুনরায় চেষ্টা করুন।</p>
                        <a href="{{ route('rentals.index') }}" class="btn btn-primary mx-auto">সকল তালিকা দেখুন</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection

