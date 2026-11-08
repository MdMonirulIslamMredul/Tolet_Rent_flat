@extends('admin.layouts.app')

@section('title', 'ইউজার ভেরিফিকেশন আবেদন - এডমিন প্যানেল')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">NID ও সেলফি ভেরিফিকেশন কিউ (Trust Verification)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                    <li class="breadcrumb-item active">ভেরিফিকেশন</li>
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
                <h5 class="card-title fw-bold mb-0">ভেরিফিকেশন অনুরোধ তালিকা</h5>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.rentbd.verifications.index', ['status' => 'pending']) }}" class="btn {{ $status == 'pending' ? 'btn-primary' : 'btn-outline-secondary' }}">বিচারাধীন (Pending)</a>
                    <a href="{{ route('admin.rentbd.verifications.index', ['status' => 'approved']) }}" class="btn {{ $status == 'approved' ? 'btn-primary' : 'btn-outline-secondary' }}">অনুমোদিত (Approved)</a>
                    <a href="{{ route('admin.rentbd.verifications.index', ['status' => 'rejected']) }}" class="btn {{ $status == 'rejected' ? 'btn-primary' : 'btn-outline-secondary' }}">বাতিল (Rejected)</a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ব্যবহারকারী</th>
                                <th>রোল</th>
                                <th>এনআইডি নম্বর</th>
                                <th>এনআইডি ছবি (Front/Back)</th>
                                <th>সেলফি ছবি</th>
                                <th>তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-right">অনুমোদন অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($verifications as $v)
                                <tr>
                                    <td>
                                        <strong>{{ $v->user?->name }}</strong>
                                        <div class="text-muted small">{{ $v->user?->phone ?: $v->user?->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $v->user?->role ?: 'User' }}</span>
                                    </td>
                                    <td><strong>{{ $v->nid_number ?: $v->user?->nid_number }}</strong></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($v->nid_front_path)
                                                <a href="{{ asset($v->nid_front_path) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                                    সামনের পাশ
                                                </a>
                                            @endif
                                            @if($v->nid_back_path)
                                                <a href="{{ asset($v->nid_back_path) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                    পেছনের পাশ
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($v->selfie_path)
                                            <a href="{{ asset($v->selfie_path) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                সেলফি দেখুন
                                            </a>
                                        @else
                                            <span class="text-muted small">নেই</span>
                                        @endif
                                    </td>
                                    <td>{{ $v->created_at->format('d M, Y') }}</td>
                                    <td>
                                        @if($v->status === 'approved')
                                            <span class="badge badge-success">অনুমোদিত</span>
                                        @elseif($v->status === 'rejected')
                                            <span class="badge badge-danger">বাতিল</span>
                                        @else
                                            <span class="badge badge-warning">অপেক্ষমান</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($v->status === 'pending')
                                            <div class="btn-group">
                                                <form action="{{ route('admin.rentbd.verifications.approve', $v->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('এই ব্যবহারকারীকে Verified ব্যাজ অনুমোদন করতে চান?')">
                                                        <i class="fas fa-check"></i> অনুমোদন
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small">প্রসেসড</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">কোনো আবেদন নেই।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($verifications->hasPages())
                <div class="card-footer bg-white">
                    {{ $verifications->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

