<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalUnit extends Model
{
    use HasFactory;

    protected $table = 'rental_units';

    protected $fillable = [
        'unit_id',
        'building_id',
        'category_type',
        'category',
        'title',
        'unit_number',
        'floor_level',
        'size_sqft',
        'bedrooms',
        'bathrooms',
        'balconies',
        'facing',
        'monthly_rent',
        'service_charge',
        'security_deposit',
        'rent_negotiable',
        'gas_type',
        'water_supply',
        'electricity_type',
        'preferred_tenant',
        'available_from',
        'is_vacant',
        'status',
        'amenities',
        'thumbnail',
        'gallery_images',
        'floor_plan_image',
        'video_url',
        'notes',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'rent_negotiable' => 'boolean',
        'is_vacant' => 'boolean',
        'size_sqft' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'balconies' => 'integer',
        'available_from' => 'date',
        'amenities' => 'array',
        'gallery_images' => 'array',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function owner()
    {
        return $this->building->owner();
    }

    public function applications()
    {
        return $this->hasMany(RentalApplication::class, 'unit_id');
    }

    public function agreements()
    {
        return $this->hasMany(DigitalAgreement::class, 'unit_id');
    }

    public function reviews()
    {
        return $this->hasMany(RatingReview::class, 'unit_id');
    }

    public function toggleVacancy(): bool
    {
        $this->is_vacant = !$this->is_vacant;
        $this->status = $this->is_vacant ? 'vacant' : 'occupied';
        $this->save();

        if ($this->building) {
            $this->building->recalculateCounts();
        }

        return $this->is_vacant;
    }

    public function getRentAttribute(): float
    {
        return (float) ($this->monthly_rent ?? 0);
    }

    public function getFloorAttribute(): ?string
    {
        return $this->floor_level;
    }

    public function getCategoryLabelAttribute(): string
    {
        $labels = [
            'flat' => 'ফ্ল্যাট (Flat)',
            'house' => 'বাসা (House)',
            'shop' => 'দোকান (Shop)',
            'office' => 'অফিস (Office)',
            'garage' => 'গ্যারেজ (Garage)',
            'parking' => 'পার্কিং (Parking)',
            'mess' => 'মেস (Mess)',
            'sublet' => 'সাবলেট (Sublet)',
            'hostel' => 'হোস্টেল (Hostel)',
            'showroom' => 'শোরুম (Showroom)',
            'warehouse' => 'গুদাম (Warehouse)',
            'factory' => 'ফ্যাক্টরি (Factory)',
            'roof' => 'ছাদ (Roof)',
            'land' => 'জমি (Land)',
            'land_lease' => 'জমি লিজ (Land Lease)',
        ];

        return $labels[$this->category] ?? ucfirst($this->category);
    }

    public function getCategoryIconAttribute(): string
    {
        $icons = [
            'flat' => 'bi bi-building',
            'house' => 'bi bi-house-door-fill',
            'shop' => 'bi bi-shop',
            'office' => 'bi bi-briefcase-fill',
            'garage' => 'bi bi-car-front-fill',
            'parking' => 'bi bi-p-square-fill',
            'mess' => 'bi bi-people-fill',
            'sublet' => 'bi bi-door-open-fill',
            'hostel' => 'bi bi-building-check',
            'showroom' => 'bi bi-shop-window',
            'warehouse' => 'bi bi-boxes',
            'factory' => 'bi bi-gear-wide-connected',
            'roof' => 'bi bi-clouds-fill',
            'land' => 'bi bi-signpost-2-fill',
            'land_lease' => 'bi bi-signpost-2-fill',
        ];

        return $icons[$this->category] ?? 'bi bi-tag-fill';
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        $classes = [
            'flat' => 'bg-primary text-white',
            'house' => 'bg-primary text-white',
            'shop' => 'bg-success text-white',
            'office' => 'bg-info text-white',
            'garage' => 'bg-warning text-dark',
            'parking' => 'bg-warning text-dark',
            'mess' => 'bg-danger text-white',
            'sublet' => 'bg-secondary text-white',
            'hostel' => 'bg-danger text-white',
            'showroom' => 'bg-success text-white',
            'warehouse' => 'bg-dark text-white',
            'factory' => 'bg-dark text-white',
            'roof' => 'bg-warning text-dark',
            'land' => 'bg-secondary text-white',
            'land_lease' => 'bg-secondary text-white',
        ];

        return $classes[$this->category] ?? 'bg-primary text-white';
    }

    public function getCategoryNameBnAttribute(): string
    {
        $names = [
            'flat' => 'ফ্ল্যাট',
            'house' => 'বাসা / স্বতন্ত্র বাড়ি',
            'sublet' => 'সাবলেট',
            'mess' => 'মেস',
            'hostel' => 'হোস্টেল',
            'shop' => 'দোকান',
            'office' => 'অফিস',
            'showroom' => 'শোরুম',
            'warehouse' => 'গুদাম / ওয়্যারহাউজ',
            'factory' => 'ফ্যাক্টরি স্পেস',
            'garage' => 'গ্যারেজ',
            'parking' => 'পার্কিং',
            'roof' => 'ছাদ',
            'land_lease' => 'জমি লিজ',
        ];

        return $names[$this->category] ?? ucfirst($this->category);
    }

    public function getFormattedRentAttribute(): string
    {
        return number_format($this->monthly_rent) . ' ৳/মাস';
    }

    public function getSummarySpecAttribute(): string
    {
        $parts = [];
        if ($this->bedrooms) $parts[] = $this->bedrooms . ' বেড';
        if ($this->bathrooms) $parts[] = $this->bathrooms . ' বাথ';
        if ($this->size_sqft) $parts[] = $this->size_sqft . ' স্কয়ার ফিট';
        if ($this->floor_level) $parts[] = $this->floor_level;

        return implode(' • ', $parts);
    }
}

