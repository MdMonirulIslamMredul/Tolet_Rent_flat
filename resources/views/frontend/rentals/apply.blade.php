@extends('frontend.layouts.app')

@section('title', 'ভাড়ার আবেদন ফরম - ' . $unit->unit_id . ' | RentBD')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Unit Overview Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">{{ $unit->unit_id }}</span>
                            <h4 class="fw-bold text-dark mb-1">{{ $unit->title }}</h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-geo-alt-fill text-danger"></i> {{ $unit->building?->name_bn ?: $unit->building?->name }}, {{ $unit->building?->address }}
                            </p>
                        </div>
                        <div class="text-end mt-2 mt-sm-0">
                            <span class="text-muted small">মাসিক ভাড়া</span>
                            <h3 class="fw-bold text-primary mb-0">{{ number_format($unit->monthly_rent) }} ৳</h3>
                        </div>
                    </div>
                </div>

                <!-- Application Form Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <h4 class="fw-bold text-dark mb-1">ভাড়াটিয়া আবেদন ফরম (Tenant Application)</h4>
                    <p class="text-muted small mb-4">
                        সঠিক তথ্য দিয়ে ফরমটি পূরণ করুন। আপনার আবেদন বাড়িওয়ালার কাছে পৌঁছাবে এবং তিনি আপনার সাথে যোগাযোগ করবেন।
                    </p>

                    <form action="{{ route('rentals.apply.store', $unit->unit_id) }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">সম্ভাব্য ওঠার তারিখ (Proposed Move-in Date) <span class="text-danger">*</span></label>
                                <input type="date" name="proposed_move_in_date" class="form-control form-control-lg @error('proposed_move_in_date') is-invalid @enderror" value="{{ old('proposed_move_in_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                                @error('proposed_move_in_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">পরিবারের সদস্য সংখ্যা (Family Members)</label>
                                <input type="number" name="family_members_count" min="1" max="20" class="form-control form-control-lg @error('family_members_count') is-invalid @enderror" value="{{ old('family_members_count', 3) }}" placeholder="যেমন: ৩ বা ৪ জন">
                                @error('family_members_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">আপনার পেশা (Profession)</label>
                                <input type="text" name="profession" class="form-control form-control-lg @error('profession') is-invalid @enderror" value="{{ old('profession') }}" placeholder="যেমন: সরকারি চাকুরিজীবী, শিক্ষক, ব্যাংকার, ব্যবসায়ী">
                                @error('profession')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">মাসিক আয় সীমা (Monthly Income)</label>
                                <select name="monthly_income_range" class="form-select form-select-lg">
                                    <option value="20k-40k">২০,০০০ - ৪০,০০০ ৳</option>
                                    <option value="40k-60k" selected>৪০,০০০ - ৬০,০০০ ৳</option>
                                    <option value="60k-100k">৬০,০০০ - ১,০০,০০০ ৳</option>
                                    <option value="100k+">১,০০,০০০ ৳ এর বেশি</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">বর্তমান ঠিকানা (Current Address)</label>
                                <input type="text" name="current_address" class="form-control form-control-lg" value="{{ old('current_address', $user?->address) }}" placeholder="আপনার বর্তমান বাসার ঠিকানা">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">বাড়িওয়ালার প্রতি বার্তা বা বিশেষ চাহিদা (Message to Landlord)</label>
                                <textarea name="message_to_owner" rows="4" class="form-control" placeholder="আপনার পরিবার, সম্ভাব্য ওঠার তারিখ বা বিশেষ কোনো তথ্য থাকলে এখানে লিখুন...">{{ old('message_to_owner') }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow">
                                    <i class="bi bi-send-fill me-2"></i> আবেদন জমা দিন (Submit Application)
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

