@extends('frontend.layouts.app')

@section('title', 'ড্যাশবোর্ড - ' . $user->name . ' | RentBD')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <!-- User Welcome Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">ইউজার অ্যাকাউন্ট</span>
                    <h2 class="fw-bold text-dark mb-1">স্বাগতম, {{ $user->name }}</h2>
                    <p class="text-secondary mb-0">
                        <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                        @if($user->phone)
                            • <i class="bi bi-telephone ms-2 me-1"></i> {{ $user->phone }}
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($user->isVerified())
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6 rounded-pill">
                            <i class="bi bi-patch-check-fill text-success me-1"></i> যাচাইকৃত ইউজার (Verified)
                        </span>
                    @else
                        <a href="{{ route('tenant.verification.index') }}" class="btn btn-warning fw-bold shadow-sm">
                            <i class="bi bi-shield-lock me-1"></i> NID ও সেলফি ভেরিফাই করুন
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Portals Grid -->
        <div class="row g-4">
            <!-- Owner Portal Card -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow transition">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-3 bg-primary-subtle text-primary rounded-4 display-6">
                            <i class="bi bi-building"></i>
                        </div>
                        <span class="badge bg-primary px-3 py-2 rounded-pill">মালিক প্যানেল</span>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">বাড়িওয়ালা ড্যাশবোর্ড (Landlord Portal)</h4>
                    <p class="text-secondary mb-4">
                        নতুন ভবন ও ফ্ল্যাট/দোকান যোগ করুন, ১-ক্লিকে রিয়েল-টাইম ভ্যাকেন্সি অন/অফ করুন, গেট পোস্টার কিউআর কোড ডাউনলোড ও প্রিন্ট করুন এবং ভাড়াটিয়ার আবেদন পর্যালোচনা করুন।
                    </p>

                    <div class="mt-auto">
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                            বাড়িওয়ালা ড্যাশবোর্ডে প্রবেশ করুন →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tenant Portal Card -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow transition">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-3 bg-success-subtle text-success rounded-4 display-6">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <span class="badge bg-success px-3 py-2 rounded-pill">ভাড়াটিয়া প্যানেল</span>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">ভাড়াটিয়া ড্যাশবোর্ড (Tenant Portal)</h4>
                    <p class="text-secondary mb-4">
                        আপনার ভাড়ার আবেদনসমূহের স্ট্যাটাস ট্র্যাক করুন, সংরক্ষিত পছন্দের প্রপার্টি ও গেটে স্ক্যান করা ভবন দেখুন এবং ডিজিটাল ভাড়া চুক্তিপত্র ডাউনলোড করুন।
                    </p>

                    <div class="mt-auto">
                        <a href="{{ route('tenant.dashboard') }}" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                            ভাড়াটিয়া ড্যাশবোর্ডে প্রবেশ করুন →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
