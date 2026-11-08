<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'buildings';

    protected $fillable = [
        'building_id',
        'owner_id',
        'name',
        'name_bn',
        'division',
        'district',
        'district_code',
        'thana_upazila',
        'area',
        'address',
        'latitude',
        'longitude',
        'total_floors',
        'total_units',
        'vacant_units_count',
        'amenities',
        'thumbnail',
        'gallery_images',
        'video_url',
        'qr_code_path',
        'is_featured',
        'status',
        'description',
        'contact_person',
        'contact_phone',
        'contact_whatsapp',
    ];

    protected $casts = [
        'amenities' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_floors' => 'integer',
        'total_units' => 'integer',
        'vacant_units_count' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getThanaAttribute(): ?string
    {
        return $this->thana_upazila;
    }

    public function units()
    {
        return $this->hasMany(RentalUnit::class, 'building_id');
    }

    public function vacantUnits()
    {
        return $this->units()->where('is_vacant', true);
    }

    public function occupiedUnits()
    {
        return $this->units()->where('is_vacant', false);
    }

    public function applications()
    {
        return $this->hasMany(RentalApplication::class, 'building_id');
    }

    public function qrScans()
    {
        return $this->hasMany(QrScanLog::class, 'building_id');
    }

    public function reviews()
    {
        return $this->hasMany(RatingReview::class, 'building_id');
    }

    public function recalculateCounts(): void
    {
        $this->total_units = $this->units()->count();
        $this->vacant_units_count = $this->units()->where('is_vacant', true)->count();
        $this->save();
    }

    public function getQrUrlAttribute(): string
    {
        return url('/b/' . $this->building_id);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name_bn ? "{$this->name_bn} ({$this->name})" : $this->name;
    }

    public function getRentRangeAttribute(): string
    {
        $min = $this->units()->min('monthly_rent');
        $max = $this->units()->max('monthly_rent');

        if (!$min) {
            return 'আলোচনা সাপেক্ষ';
        }

        if ($min == $max) {
            return number_format($min) . ' ৳';
        }

        return number_format($min) . ' - ' . number_format($max) . ' ৳';
    }

    public function getVacancyBadgeAttribute(): array
    {
        if ($this->vacant_units_count > 0) {
            return [
                'type' => 'vacant',
                'color' => 'success',
                'bg' => '#10b981',
                'text' => $this->vacant_units_count . 'টি খালি ইউনিট',
                'status_bn' => 'ভাড়া হবে (খালি আছে)',
            ];
        }

        return [
            'type' => 'occupied',
            'color' => 'danger',
            'bg' => '#ef4444',
            'text' => 'সম্পূর্ণ বুকড',
            'status_bn' => 'বর্তমানে কোনো ইউনিট খালি নেই',
        ];
    }
}

