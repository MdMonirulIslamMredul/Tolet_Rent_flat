@extends('admin.layouts.app')
@section('title', 'Admin Dashboard - RentBD Smart QR Rental Platform')

@push('styles')
<style>
    .kpi-card {
        border: none;
        border-radius: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #ffffff;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }
    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .quick-link-item {
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        transition: all 0.15s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .quick-link-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #0d6efd;
        transform: translateX(3px);
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper pb-4">

    {{-- Welcome Banner --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                            <i class="bi bi-upc-scan me-1"></i> RentBD Smart QR Control Center
                        </span>
                        <span class="text-secondary small">|</span>
                        <span class="text-secondary small">{{ now()->format('l, d M Y') }}</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-white">স্বাগতম, {{ auth()->user()->name }}! 👋</h3>
                    <p class="text-secondary mb-0" style="font-size: 0.95rem;">
                        RentBD রেন্টাল ইকোসিস্টেমের রিয়েল-টাইম ভবন, ভ্যাকেন্সি, ভেরিফিকেশন ও ভাড়া আবেদনের সার্বিক অবস্থা।
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('admin.rentbd.buildings.index') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="bi bi-building me-1"></i> ভবনসমূহ দেখুন
                        </a>
                        <a href="{{ route('rentals.map') }}" target="_blank" class="btn btn-outline-light btn-sm px-3">
                            <i class="bi bi-map me-1"></i> ভ্যাকেন্সি ম্যাপ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Core RentBD KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">নিবন্ধিত ভবন</span>
                    <div class="kpi-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalBuildings) }}</h3>
                <div class="text-muted small">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> টাঙ্গাইল সদর (Phase 1)
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">খালি ভাড়ার ইউনিট</span>
                    <div class="kpi-icon-box bg-success-subtle text-success">
                        <i class="bi bi-door-open"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-1">{{ number_format($vacantUnits) }}</h3>
                <div class="text-muted small">
                    মোট {{ number_format($totalUnits) }} ইউনিটের মধ্যে ({{ number_format($occupiedUnits) }} বুকড)
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">ভাড়ার আবেদন</span>
                    <div class="kpi-icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-inbox"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ number_format($totalApplications) }}</h3>
                <div class="text-muted small">
                    <span class="badge bg-warning text-dark">{{ $pendingApplications }} পেন্ডিং</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">NID যাচাইকৃত ব্যবহারকারী</span>
                    <div class="kpi-icon-box bg-info-subtle text-info">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-info mb-1">{{ number_format($verifiedUsers) }}</h3>
                <div class="text-muted small">
                    <span class="badge bg-danger">{{ $pendingVerifications }} পেন্ডিং ভেরিফিকেশন</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Metrics --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-box bg-dark text-white rounded-3">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">গেট কিউআর স্ক্যান লগ</div>
                        <h4 class="fw-bold mb-0">{{ number_format($totalQrScans) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-box bg-primary text-white rounded-3">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">ডিজিটাল ভাড়া চুক্তিপত্র</div>
                        <h4 class="fw-bold mb-0">{{ number_format($totalAgreements) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-box bg-secondary text-white rounded-3">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">যোগাযোগ ইনকোয়ারি</div>
                        <h4 class="fw-bold mb-0">{{ number_format($contentStats['contacts']) }} <small class="text-danger fs-6">({{ $contentStats['unread_contacts'] }} অপঠিত)</small></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Tables Row --}}
    <div class="row g-4 mb-4">
        {{-- Recent Buildings --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-building me-2 text-primary"></i>সাম্প্রতিক নিবন্ধিত ভবনসমূহ
                    </h5>
                    <a href="{{ route('admin.rentbd.buildings.index') }}" class="btn btn-sm btn-outline-primary">
                        সকল ভবন →
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Property ID</th>
                                <th>ভবনের নাম</th>
                                <th>এলাকা</th>
                                <th>খালি ইউনিট</th>
                                <th>পোস্টার / কিউআর</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBuildings as $b)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">{{ $b->building_id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $b->name_bn ?: $b->name }}</div>
                                        <small class="text-muted">{{ $b->owner?->name }}</small>
                                    </td>
                                    <td>
                                        <span class="text-secondary small">{{ $b->area }}, {{ $b->thana }}</span>
                                    </td>
                                    <td>
                                        @if($b->vacant_units_count > 0)
                                            <span class="badge bg-success">{{ $b->vacant_units_count }}টি খালি</span>
                                        @else
                                            <span class="badge bg-secondary">বুকড</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('qr.building.show', $b->building_id) }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2" title="কিউআর পেজ">
                                            <i class="bi bi-qr-code"></i>
                                        </a>
                                        <a href="{{ route('qr.building.poster', $b->building_id) }}" target="_blank" class="btn btn-xs btn-outline-secondary py-1 px-2" title="গেট পোস্টার">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">কোনো ভবন পাওয়া যায়নি</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Links & Pending Verifications --}}
        <div class="col-lg-5">
            {{-- Pending Verifications Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-shield-exclamation me-2 text-warning"></i>পেন্ডিং NID ভেরিফিকেশন
                    </h5>
                    <a href="{{ route('admin.rentbd.verifications.index') }}" class="btn btn-sm btn-outline-warning">
                        যাচাই করুন →
                    </a>
                </div>
                <div class="card-body p-3">
                    @forelse($recentVerifications as $v)
                        <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded bg-light border">
                            <div>
                                <div class="fw-bold text-dark">{{ $v->user?->name }}</div>
                                <small class="text-muted">NID: {{ $v->nid_number }} | {{ $v->user?->role }}</small>
                            </div>
                            <a href="{{ route('admin.rentbd.verifications.index') }}" class="btn btn-sm btn-primary">
                                রিভিউ
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                            কোনো পেন্ডিং ভেরিফিকেশন নেই
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Management Links --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-lightning-charge me-2 text-primary"></i>কুইক নেভিগেশন
                    </h5>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2">
                    <a href="{{ route('admin.rentbd.buildings.index') }}" class="quick-link-item">
                        <span><i class="bi bi-building me-2 text-primary"></i>ভবন ও কিউআর তালিকা</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('admin.rentbd.verifications.index') }}" class="quick-link-item">
                        <span><i class="bi bi-shield-check me-2 text-success"></i>ইউজার NID ভেরিফিকেশন</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="quick-link-item">
                        <span><i class="bi bi-tools me-2 text-warning"></i>রেন্টাল সার্ভিস ও ফি সেটিংস</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('rentals.map') }}" target="_blank" class="quick-link-item">
                        <span><i class="bi bi-map me-2 text-danger"></i>লাইভ ভ্যাকেন্সি ম্যাপ (পাবলিক)</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                    <a href="{{ route('rentals.index') }}" target="_blank" class="quick-link-item">
                        <span><i class="bi bi-search me-2 text-info"></i>রেন্টাল ডিরেক্টরি ও AI সার্চ</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
