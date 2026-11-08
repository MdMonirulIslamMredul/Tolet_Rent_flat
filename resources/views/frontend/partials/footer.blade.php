@php
    $footerSetting = $settings ?? null;
    $social = $footerSetting?->social_links ?? [];

    $mapEmbed = $footerSetting?->google_map_embed;
    $mapSrc = null;
    if (!empty($mapEmbed)) {
        if (preg_match('/src=["\']([^"\']+)["\']/', $mapEmbed, $matches)) {
            $mapSrc = $matches[1];
        } else {
            $mapSrc = $mapEmbed;
        }
    }
    if (!$mapSrc && !empty($footerSetting?->contact_address)) {
        $mapSrc = 'https://maps.google.com/maps?q=' . urlencode($footerSetting->contact_address) . '&t=&z=13&ie=UTF8&iwloc=&output=embed';
    }
@endphp

<footer class="pt-5 pb-3 mt-5 position-relative">
    <div class="container">
        <div class="row g-4 justify-content-between">
            {{-- Col 1: Brand & Contact Info --}}
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <a href="{{ route('home') }}" class="d-inline-block">
                        @if (!empty($footerSetting?->logo_path) && file_exists(storage_path('app/public/' . $footerSetting->logo_path)))
                            <img src="{{ asset('storage/' . $footerSetting->logo_path) }}"
                                alt="{{ $footerSetting?->site_name ?? 'RentBD' }} logo"
                                style="max-height: 48px; width: auto;">
                        @else
                            <img src="{{ asset('images/logo-white.svg') }}"
                                alt="RentBD"
                                style="max-height: 48px; width: auto;">
                        @endif
                    </a>
                </div>
                <p class="small text-secondary mb-3 pe-lg-3 leading-relaxed">
                    {{ strip_tags($footerSetting?->about_content ?: ($footerSetting?->company_intro ?? 'বাংলাদেশের প্রথম কিউআর কোড ভিত্তিক স্মার্ট রেন্টাল প্ল্যাটফর্ম। রিয়েল-টাইম খালি ফ্ল্যাট, বাসা, দোকান ও গ্যারেজ খুঁজুন এবং বাড়িওয়ালা ও ভাড়াটিয়াকে সরাসরি সংযুক্ত করুন।')) }}
                </p>
                <div class="d-flex flex-column gap-2 small text-secondary">
                    @if ($footerSetting?->contact_address)
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                            <span>{{ $footerSetting->contact_address }}</span>
                        </div>
                    @endif
                    @if ($footerSetting?->contact_email)
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill text-primary"></i>
                            <a href="mailto:{{ $footerSetting->contact_email }}" class="text-secondary text-decoration-none hover-white">
                                {{ $footerSetting->contact_email }}
                            </a>
                        </div>
                    @endif
                    @if ($footerSetting?->contact_phone)
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-fill text-success"></i>
                            <a href="tel:{{ $footerSetting->contact_phone }}" class="text-secondary text-decoration-none hover-white">
                                {{ $footerSetting->contact_phone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-link-45deg text-warning"></i> প্রয়োজনীয় লিংক
                </h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('home') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> হোমপেজ (Home)
                    </a>
                    <a href="{{ route('rentals.index') }}" class="footer-nav-link text-decoration-none text-warning fw-bold">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> খালি বাসা খুঁজুন (Find Rentals)
                    </a>
                    <a href="{{ route('rentals.map') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> লাইভ ভ্যাকেন্সি ম্যাপ (Live Map)
                    </a>
                    <a href="{{ route('owner.dashboard') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> বাড়িওয়ালা পোর্টাল (Owner)
                    </a>
                    <a href="{{ route('tenant.dashboard') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> ভাড়াটিয়া পোর্টাল (Tenant)
                    </a>
                    <a href="{{ route('about') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> আমাদের সম্পর্কে (About Us)
                    </a>
                    <a href="{{ route('contact.index') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-warning opacity-50"></i> যোগাযোগ (Contact)
                    </a>
                </div>
            </div>

            {{-- Col 3: Follow Us & Social Media --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-share-fill text-primary"></i> Follow Us
                </h6>
                @php
                    $hasFacebook = !empty($social['facebook']);
                    $hasTwitter = !empty($social['twitter']) || !empty($social['x']);
                    $hasInstagram = !empty($social['instagram']);
                    $hasLinkedin = !empty($social['linkedin']);
                    $hasYoutube = !empty($social['youtube']);
                    $hasAnySocial = $hasFacebook || $hasTwitter || $hasInstagram || $hasLinkedin || $hasYoutube;
                @endphp
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if ($hasFacebook || !$hasAnySocial)
                        <a class="footer-social-btn facebook" href="{{ $social['facebook'] ?? '#' }}" target="_blank" rel="noopener" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    @endif
                    @if ($hasTwitter || !$hasAnySocial)
                        <a class="footer-social-btn twitter" href="{{ $social['twitter'] ?? ($social['x'] ?? '#') }}" target="_blank" rel="noopener" title="X (Twitter)">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    @endif
                    @if ($hasInstagram || !$hasAnySocial)
                        <a class="footer-social-btn instagram" href="{{ $social['instagram'] ?? '#' }}" target="_blank" rel="noopener" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    @endif
                    @if ($hasLinkedin || !$hasAnySocial)
                        <a class="footer-social-btn linkedin" href="{{ $social['linkedin'] ?? '#' }}" target="_blank" rel="noopener" title="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    @endif
                    @if ($hasYoutube || !$hasAnySocial)
                        <a class="footer-social-btn youtube" href="{{ $social['youtube'] ?? '#' }}" target="_blank" rel="noopener" title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    @endif
                </div>
                <div class="small text-secondary">
                    Stay connected with our community and project showcases across social channels.
                </div>
            </div>

            {{-- Col 4: Google Map Embed View --}}
            <div class="col-lg-4 col-md-12">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-white fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i> Our Location
                    </h6>
                    @if ($mapSrc)
                        <a href="{{ $mapSrc }}" target="_blank" rel="noopener" class="small text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                            Full Map <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                    @endif
                </div>
                <div class="rounded-4 overflow-hidden shadow-sm border border-secondary border-opacity-25" style="height: 180px; width: 100%; background: #0f172a;">
                    <iframe src="{{ $mapSrc ?: 'https://maps.google.com/maps?q=Dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed' }}"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <hr class="border-secondary border-opacity-25 my-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <p class="small text-secondary mb-0">
                &copy; {{ date('Y') }} <strong class="text-light">{{ $footerSetting?->site_name ?? 'RentBD' }}</strong> – বাংলাদেশের প্রথম স্মার্ট কিউআর রেন্টাল প্ল্যাটফর্ম (টাঙ্গাইল সদর ও বাংলাদেশ)।
            </p>
            <div class="small text-secondary">
                Developed by <a href="https://www.techwebdit.com/" target="_blank" rel="noopener noreferrer" class="text-warning text-decoration-none fw-semibold" title="Techweb Bd It">Techweb Bd It</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-nav-link {
        color: var(--footer-text, #cbd5e1);
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .footer-nav-link:hover {
        color: var(--accent-color, #f39c12);
        transform: translateX(4px);
    }
    .footer-social-btn {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0;
        transition: all 0.25s ease-in-out;
        text-decoration: none;
        font-size: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .footer-social-btn:hover {
        transform: translateY(-3px);
        color: #ffffff;
    }
    .footer-social-btn.facebook:hover {
        background: #1877f2;
        border-color: #1877f2;
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.4);
    }
    .footer-social-btn.twitter:hover {
        background: #000000;
        border-color: #333333;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }
    .footer-social-btn.instagram:hover {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(214, 36, 159, 0.4);
    }
    .footer-social-btn.linkedin:hover {
        background: #0a66c2;
        border-color: #0a66c2;
        box-shadow: 0 4px 12px rgba(10, 102, 194, 0.4);
    }
    .footer-social-btn.youtube:hover {
        background: #ff0000;
        border-color: #ff0000;
        box-shadow: 0 4px 12px rgba(255, 0, 0, 0.4);
    }
    .hover-white:hover {
        color: #ffffff !important;
    }
</style>
