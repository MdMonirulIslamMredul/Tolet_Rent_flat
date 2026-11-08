@extends('frontend.layouts.app')

@section('title', 'ভ্যাকেন্সি ম্যাপ ভিউ (Vacancy Map) - রিয়েল-টাইম খালি বাসা ও দোকান | RentBD')

@section('content')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    #vacancyMap {
        height: calc(100vh - 160px);
        min-height: 550px;
        width: 100%;
        border-radius: 16px;
        z-index: 1;
    }
    .map-filter-card {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        max-width: 340px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .map-legend {
        position: absolute;
        bottom: 30px;
        right: 20px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        font-size: 13px;
        font-weight: 600;
    }
    .marker-pin {
        width: 32px;
        height: 32px;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin: -16px 0 0 -16px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.3);
        border: 2px solid #ffffff;
    }
    .marker-pin::after {
        content: '';
        width: 14px;
        height: 14px;
        margin: 7px 0 0 7px;
        background: #ffffff;
        position: absolute;
        border-radius: 50%;
    }
    .marker-green { background: #10b981; }
    .marker-red { background: #ef4444; }
    .marker-blue { background: #0284c7; }
</style>

<div class="container-fluid px-3 py-3 position-relative">
    <!-- Map Container -->
    <div id="vacancyMap"></div>

    <!-- Filter Overlay Card -->
    <div class="map-filter-card p-3 d-none d-md-block">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-dark mb-0">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> ভ্যাকেন্সি ম্যাপ ফিল্টার
            </h6>
            <span class="badge bg-primary-subtle text-primary" id="buildingCountBadge">০ টি প্রপার্টি</span>
        </div>
        <p class="small text-muted mb-3">টাঙ্গাইল সদর সহ যেকোনো এলাকার রিয়েল-টাইম খালি বাসা ও দোকান দেখুন</p>

        <form id="mapFilterForm">
            <div class="mb-2">
                <label class="small fw-semibold text-secondary">জেলা (District)</label>
                <select class="form-select form-select-sm" id="districtSelect" name="district">
                    <option value="Tangail" selected>টাঙ্গাইল (Tangail)</option>
                    <option value="Dhaka">ঢাকা (Dhaka)</option>
                    <option value="Gazipur">গাজীপুর (Gazipur)</option>
                </select>
            </div>

            <div class="mb-2">
                <label class="small fw-semibold text-secondary">এলাকা (Area in Tangail)</label>
                <select class="form-select form-select-sm" id="areaSelect" name="area">
                    <option value="">সকল এলাকা</option>
                    <option value="Akur Takur Para">আকুর টাকুর পাড়া</option>
                    <option value="Biswas Betka">বিশ্বাস বেতকা</option>
                    <option value="Victoria Road">ভিক্টোরিয়া রোড</option>
                    <option value="College Mor">কলেজ মোড়</option>
                    <option value="Adalat Para">আদালত পাড়া</option>
                </select>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="vacantOnlyCheck" name="vacant_only" value="1" checked>
                <label class="form-check-label small fw-semibold" for="vacantOnlyCheck">
                    শুধু খালি প্রপার্টি দেখান (Only Vacant)
                </label>
            </div>

            <button type="button" class="btn btn-primary btn-sm w-100 fw-bold" onclick="loadMapMarkers()">
                <i class="bi bi-search me-1"></i> ম্যাপ রিফ্রেশ করুন
            </button>
        </form>
    </div>

    <!-- Legend Overlay -->
    <div class="map-legend">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-success me-1">&nbsp;</span> খালি ইউনিট আছে (Vacant)
            </div>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-danger me-1">&nbsp;</span> সম্পূর্ণ বুকড (Occupied)
            </div>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-primary me-1">&nbsp;</span> ফিচার্ড (Featured)
            </div>
        </div>
    </div>
</div>

<script>
    let map;
    let markersLayer = L.layerGroup();

    function initMap() {
        // Center on Tangail Sadar
        map = L.map('vacancyMap').setView([24.2513, 89.9167], 14);

        // OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors | RentBD Smart QR'
        }).addTo(map);

        markersLayer.addTo(map);

        loadMapMarkers();
    }

    function createCustomMarkerIcon(color) {
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="marker-pin marker-${color}"></div>`,
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });
    }

    function loadMapMarkers() {
        const district = document.getElementById('districtSelect')?.value || 'Tangail';
        const area = document.getElementById('areaSelect')?.value || '';
        const vacantOnly = document.getElementById('vacantOnlyCheck')?.checked ? '1' : '0';

        const params = new URLSearchParams({
            district: district,
            area: area,
            vacant_only: vacantOnly
        });

        fetch(`{{ route('api.map.markers') }}?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                markersLayer.clearLayers();

                if (document.getElementById('buildingCountBadge')) {
                    document.getElementById('buildingCountBadge').innerText = `${data.count} টি প্রপার্টি`;
                }

                if (data.buildings && data.buildings.length > 0) {
                    const bounds = [];

                    data.buildings.forEach(b => {
                        const icon = createCustomMarkerIcon(b.marker_color);
                        const marker = L.marker([b.lat, b.lng], { icon: icon });

                        const popupContent = `
                            <div style="min-width: 220px; font-family: 'Hind Siliguri', sans-serif;">
                                <h6 class="fw-bold mb-1 text-dark">${b.name_bn || b.name}</h6>
                                <div class="badge bg-primary mb-2">${b.building_id}</div>
                                <div class="small text-muted mb-2">
                                    <i class="bi bi-geo-alt-fill text-danger"></i> ${b.area}, ${b.thana}
                                </div>
                                <div class="p-2 bg-light rounded mb-2">
                                    <div class="fw-bold text-${b.vacant_units > 0 ? 'success' : 'danger'} small">
                                        ${b.vacant_units > 0 ? '✓ ' + b.vacant_units + 'টি ইউনিট খালি আছে' : '✕ সম্পূর্ণ বুকড'}
                                    </div>
                                    <div class="text-secondary small">ভাড়া: <strong>${b.rent_range}</strong></div>
                                </div>
                                <a href="${b.qr_url}" class="btn btn-sm btn-primary w-100 fw-bold">
                                    <i class="bi bi-upc-scan me-1"></i> বিস্তারিত ও খালি ফ্ল্যাট দেখুন
                                </a>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        markersLayer.addLayer(marker);
                        bounds.push([b.lat, b.lng]);
                    });

                    if (bounds.length > 0) {
                        map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });
                    }
                }
            })
            .catch(err => console.error("Error loading map markers:", err));
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection

