@extends('admin.layouts.app')

@section('title', 'RentBD ভবনসমূহ - এডমিন প্যানেল')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">RentBD ভবনসমূহ ও প্রপার্টি আইডি তালিকা</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                    <li class="breadcrumb-item active">RentBD ভবন</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">নিবন্ধিত সকল ভবন (Unique Property ID)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>বিল্ডিং আইডি</th>
                                <th>ভবনের নাম</th>
                                <th>মালিক</th>
                                <th>এলাকা ও জেলা</th>
                                <th>মোট / খালি ইউনিট</th>
                                <th>ফিচার্ড</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buildings as $b)
                                <tr>
                                    <td><span class="badge badge-primary fs-6">{{ $b->building_id }}</span></td>
                                    <td>
                                        <strong>{{ $b->name_bn ?: $b->name }}</strong>
                                        <div class="text-muted small">{{ $b->name }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $b->owner?->name }}</div>
                                        <small class="text-muted">{{ $b->owner?->phone }}</small>
                                    </td>
                                    <td>{{ $b->area }}, {{ $b->district }}</td>
                                    <td>
                                        <strong>{{ $b->total_units }}</strong> টি / 
                                        <span class="text-success font-weight-bold">{{ $b->vacant_units_count }} খালি</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.rentbd.buildings.toggle-featured', $b->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-xs {{ $b->is_featured ? 'btn-warning' : 'btn-outline-secondary' }}">
                                                <i class="fas fa-star"></i> {{ $b->is_featured ? 'ফিচার্ড' : 'সাধারণ' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="badge {{ $b->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('qr.building.show', $b->building_id) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-qrcode"></i> কিউআর পেজ
                                        </a>
                                        <a href="{{ route('qr.building.poster', $b->building_id) }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="fas fa-print"></i> পোস্টার
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">কোনো ভবন পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($buildings->hasPages())
                <div class="card-footer bg-white">
                    {{ $buildings->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

