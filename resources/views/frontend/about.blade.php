@extends('frontend.layouts.app')

@section('title', $about?->title ?? ($setting->about_title ?? 'About Us'))

@section('content')
    @php
        $bannerImage = !empty($about?->banner_image)
            ? asset('storage/' . $about->banner_image)
            : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1800&q=80';
        $image1 = !empty($about?->image1)
            ? asset('storage/' . $about->image1)
            : 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80';
        $image2 = !empty($about?->image2)
            ? asset('storage/' . $about->image2)
            : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80';
        $keyValues = collect($about?->key_values ?? [])
            ->filter()
            ->values();
    @endphp

    <section class="py-5 text-white"
        style="position: relative; background: url('{{ $bannerImage }}') center/cover no-repeat;">
        <div
            style="position: absolute; inset: 0; background: linear-gradient(110deg, rgba(15, 23, 42, 0.90), rgba(245, 158, 11, 0.28));">
        </div>
        <div class="container position-relative py-3" data-aos="fade-up">
            <span class="badge bg-primary text-white px-3 py-2 rounded-pill fw-bold mb-3">আমাদের পরিচিতি</span>
            <h1 class="display-5 fw-bold mb-3">{{ $about?->title ?? ($setting->about_title ?? 'RentBD সম্পর্কে') }}</h1>
            <p class="lead mb-0 text-white-50" style="max-width: 760px;">
                {!! $about?->page_details ??
                    ($setting->about_content ?? 'বাংলাদেশের প্রথম স্মার্ট কিউআর কোড ভিত্তিক রেন্টাল প্ল্যাটফর্ম। টাঙ্গাইল সদর থেকে শুরু করে সারাদেশে নিরাপদ, স্বচ্ছ ও ডিজিটাল রেন্টাল ইকোসিস্টেম।') !!}
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            {{-- Mission & Vision Section --}}
            @php
                $missionText = $about?->mission ?? ($setting->mission ?? null);
                $visionText = $about?->vision ?? ($setting->vision ?? null);
                $historyText = $about?->history ?? ($setting->history ?? null);
            @endphp

            @if ($missionText || $visionText)
                <div class="row g-4 mb-5" data-aos="fade-up">
                    @if ($missionText)
                        <div class="{{ $visionText ? 'col-md-6' : 'col-12' }}">
                            <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                                style="border-top: 4px solid #f59e0b !important;">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning"
                                        style="width: 48px; height: 48px; font-size: 1.5rem;">
                                        <i class="bi bi-bullseye"></i>
                                    </div>
                                    <h3 class="h4 fw-bold mb-0">Our Mission <span class="fs-6 text-muted fw-normal">(আমাদের লক্ষ্য)</span></h3>
                                </div>
                                <div class="text-secondary leading-relaxed fs-6">
                                    {!! nl2br(e($missionText)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($visionText)
                        <div class="{{ $missionText ? 'col-md-6' : 'col-12' }}">
                            <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                                style="border-top: 4px solid #0f172a !important;">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary"
                                        style="width: 48px; height: 48px; font-size: 1.5rem;">
                                        <i class="bi bi-eye-fill"></i>
                                    </div>
                                    <h3 class="h4 fw-bold mb-0">Our Vision <span class="fs-6 text-muted fw-normal">(আমাদের ভিশন)</span></h3>
                                </div>
                                <div class="text-secondary leading-relaxed fs-6">
                                    {!! nl2br(e($visionText)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="row g-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="{{ $image1 }}" alt="About Image 1" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card p-4 p-lg-5 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-warning fw-semibold small">
                            <i class="bi bi-qr-code-scan"></i> স্মার্ট কিউআর ও স্থায়ী ডিজিটাল পরিচয়
                        </div>
                        <h3 class="h4 fw-bold mb-3">স্মার্ট প্রপার্টি আইডেন্টিফিকেশন</h3>
                        <div class="text-secondary leading-relaxed">{!! $about?->details1 ?? '<p>প্রতিটি ভবনের জন্য পার্মানেন্ট Building ID (যেমন: BD-TNG-000101) ও প্রিন্টযোগ্য গেট পোস্টার প্রদান, যা স্ক্যান করলেই তাৎক্ষণিক খালি ফ্ল্যাট ও দোকানের তালিকা পাওয়া যায়।</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-center mt-1">
                <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                    <img src="{{ $image2 }}" alt="About Image 2" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="card p-4 p-lg-5 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-primary fw-semibold small">
                            <i class="bi bi-shield-check"></i> ভেরিফায়েড প্রোফাইল ও ডিজিটাল চুক্তি
                        </div>
                        <h3 class="h4 fw-bold mb-3">নিরাপদ ও নির্ভরযোগ্য রেন্টাল ব্যবস্থা</h3>
                        <div class="text-secondary leading-relaxed">{!! $about?->details2 ?? '<p>জাতীয় পরিচয়পত্র ও সেলফি ফেস ম্যাচিংয়ের মাধ্যমে শতভাগ ভেরিফায়েড ব্যবহারকারী এবং আইনগতভাবে সুরক্ষিত ডিজিটাল চুক্তিপত্র (Digital Agreement)।</p>' !!}</div>
                    </div>
                </div>
            </div>

            {{-- Optional Journey / History Section --}}
            @if ($historyText)
                <div class="row g-4 mt-1" data-aos="fade-up">
                    <div class="col-12">
                        <div class="card p-4 p-lg-5 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                            style="border-left: 5px solid var(--bs-warning, #ffc107) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning"
                                    style="width: 48px; height: 48px; font-size: 1.5rem;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h3 class="h4 fw-bold mb-0">Our Journey &amp; History <span class="fs-6 text-muted fw-normal">(আমাদের অগ্রযাত্রা)</span></h3>
                            </div>
                            <div class="text-secondary leading-relaxed">
                                {!! $historyText !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-4 mt-1">
                <div class="col-md-6" data-aos="fade-up">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-warning fw-semibold small">
                            <i class="bi bi-qr-code-scan"></i> আধুনিক কিউআর প্রযুক্তি
                        </div>
                        <h4 class="h5 fw-bold mb-3">ডিজিটাল ও স্মার্ট রেন্টাল ব্যবস্থা</h4>
                        <div class="text-secondary leading-relaxed">{!! $about?->details3 ?? '<p>কিউআর কোড এবং পার্মানেন্ট প্রপার্টি আইডির মাধ্যমে বাসা খোঁজা ও ভাড়া ব্যবস্থাপনাকে করা হয়েছে ঝামেলামুক্ত।</p>' !!}</div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-info fw-semibold small">
                            <i class="bi bi-headset"></i> সার্বক্ষণিক সহায়তা
                        </div>
                        <h4 class="h5 fw-bold mb-3">২৪/৭ রেন্টাল সাপোর্ট ও সমন্বয়</h4>
                        <div class="text-secondary leading-relaxed">{!! $about?->details4 ?? '<p>ভাড়া খোঁজা থেকে শুরু করে ডিজিটাল চুক্তি সম্পাদন পর্যন্ত সবসময় আমাদের টিম সহায়তায় নিয়োজিত।</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card p-4 text-center h-100 border-0 shadow-sm rounded-4">
                        <h3 class="mb-1 text-warning fw-bold">{{ $about?->years_experience ?? 8 }}+</h3>
                        <p class="mb-0 text-secondary">বছরের অভিজ্ঞতা</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="80">
                    <div class="card p-4 text-center h-100 border-0 shadow-sm rounded-4">
                        <h3 class="mb-1 text-dark fw-bold">{{ $about?->establishment_year ?? '২০১৮' }}</h3>
                        <p class="mb-0 text-secondary">প্রতিষ্ঠিত</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="160">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                        <h5 class="mb-2 fw-bold">Key Values</h5>
                        @if ($keyValues->isNotEmpty())
                            <ul class="mb-0 ps-3 text-secondary">
                                @foreach ($keyValues as $value)
                                    <li>{{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0 text-secondary">Add key values from admin panel to show them here.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
