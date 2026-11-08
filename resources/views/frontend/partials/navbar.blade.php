@php
    $navSetting = $settings ?? null;
    $isHome = request()->routeIs('home');
@endphp
<nav class="navbar navbar-expand-lg {{ $isHome ? 'navbar-home navbar-home-overlay fixed-top' : 'sticky-top' }} border-bottom"
    data-navbar-home="{{ $isHome ? 'true' : 'false' }}">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center py-1" href="{{ route('home') }}">
            @if(!empty($navSetting?->logo_path) && file_exists(storage_path('app/public/' . $navSetting->logo_path)))
                <img src="{{ asset('storage/' . $navSetting->logo_path) }}" alt="{{ $navSetting?->site_name ?? 'RentBD' }}" style="height: 38px; width: auto;">
            @else
                <img src="{{ asset('images/logo.svg') }}" alt="RentBD" style="height: 38px; width: auto;">
            @endif
        </a>
        <div class="d-flex align-items-center d-lg-none gap-2">
            <a href="{{ route('rentals.map') }}" class="btn btn-outline-danger btn-sm fw-bold">
                <i class="bi bi-map-fill"></i> ম্যাপ
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span
                    class="navbar-toggler-icon"></span></button>
        </div>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto gap-lg-2 align-items-lg-center">
                <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('home') }}">হোম</a></li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-primary" href="{{ route('rentals.index') }}">
                        <i class="bi bi-search me-1"></i> খালি বাসা ও দোকান
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-danger" href="{{ route('rentals.map') }}">
                        <i class="bi bi-geo-alt-fill me-1"></i> ভ্যাকেন্সি ম্যাপ (Live Map)
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">সেবাসমূহ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">আমাদের সম্পর্কে</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact.index') }}">যোগাযোগ</a></li>

                @guest
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 fw-bold">
                            লগইন
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm">
                            রেজিস্ট্রেশন
                        </a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle btn btn-light btn-sm px-3 py-1 fw-bold border text-dark" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-primary me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('owner.dashboard') }}">
                                    <i class="bi bi-building text-primary me-2"></i> বাড়িওয়ালা ড্যাশবোর্ড (Owner)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('tenant.dashboard') }}">
                                    <i class="bi bi-house-door text-success me-2"></i> ভাড়াটিয়া ড্যাশবোর্ড (Tenant)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('tenant.verification.index') }}">
                                    <i class="bi bi-shield-check text-warning me-2"></i> NID ভেরিফিকেশন
                                </a>
                            </li>
                            @if (auth()->user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> সুপার এডমিন প্যানেল
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> লগআউট
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
