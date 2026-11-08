<?php

namespace App\Services;

use App\Models\Rental\Building;
use App\Models\Rental\RentalUnit;
use Illuminate\Support\Str;

class PropertyIdService
{
    /**
     * Common district codes in Bangladesh
     */
    protected static array $districtCodes = [
        'Tangail' => 'TNG',
        'Dhaka' => 'DHA',
        'Gazipur' => 'GAZ',
        'Narayanganj' => 'NRG',
        'Chattogram' => 'CTG',
        'Sylhet' => 'SYL',
        'Rajshahi' => 'RAJ',
        'Khulna' => 'KHU',
        'Barishal' => 'BAR',
        'Rangpur' => 'RAN',
        'Mymensingh' => 'MYM',
        'Cumilla' => 'CUM',
        'Bogura' => 'BOG',
        'Kishoreganj' => 'KIS',
        'Manikganj' => 'MAN',
    ];

    /**
     * Get 3-letter district code
     */
    public static function getDistrictCode(?string $district): string
    {
        if (empty($district)) {
            return 'TNG';
        }

        // Check if district already passed as 3-letter code
        if (strlen($district) === 3 && ctype_upper($district)) {
            return $district;
        }

        $districtNormalized = ucfirst(strtolower(trim($district)));

        return self::$districtCodes[$districtNormalized] ?? strtoupper(substr($districtNormalized, 0, 3));
    }

    /**
     * Generate unique Building ID: e.g. BD-TNG-000101
     */
    public static function generateBuildingId(string $district = 'Tangail'): string
    {
        $code = self::getDistrictCode($district);
        $prefix = "BD-{$code}-";

        // Find highest existing serial for this district
        $latest = Building::where('building_id', 'like', "{$prefix}%")
            ->orderByDesc('building_id')
            ->value('building_id');

        $nextSerial = 101; // Start from 000101 as per requirement specification
        if ($latest) {
            $parts = explode('-', $latest);
            $lastNum = (int) end($parts);
            if ($lastNum >= 101) {
                $nextSerial = $lastNum + 1;
            }
        }

        do {
            $buildingId = sprintf('BD-%s-%06d', $code, $nextSerial);
            $exists = Building::where('building_id', $buildingId)->exists();
            if ($exists) {
                $nextSerial++;
            }
        } while ($exists);

        return $buildingId;
    }

    /**
     * Generate unique Unit ID: e.g. BD-TNG-000101-F3B, BD-TNG-000101-S01, BD-TNG-000101-G01
     */
    public static function generateUnitId(Building $building, string $category, string $unitNumber, ?int $floor = null): string
    {
        $categoryPrefixes = [
            'flat' => 'F',
            'house' => 'H',
            'sublet' => 'SB',
            'mess' => 'M',
            'hostel' => 'HST',
            'shop' => 'S',
            'office' => 'O',
            'showroom' => 'SR',
            'warehouse' => 'W',
            'factory' => 'FC',
            'garage' => 'G',
            'parking' => 'P',
            'roof' => 'R',
            'land_lease' => 'L',
        ];

        $prefix = $categoryPrefixes[strtolower($category)] ?? 'U';

        // Clean unit number for identifier (e.g. "3B" -> "3B", "Shop-1" -> "01", "G-1" -> "01")
        $cleanNumber = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $unitNumber));
        if (empty($cleanNumber)) {
            $existingCount = RentalUnit::where('building_id', $building->id)
                ->where('category', $category)
                ->count();
            $cleanNumber = sprintf('%02d', $existingCount + 1);
        }

        // For flat with floor, e.g. floor 3, unit B -> F3B
        $candidateUnitCode = "{$prefix}{$cleanNumber}";
        $candidateId = "{$building->building_id}-{$candidateUnitCode}";

        // Ensure uniqueness
        $counter = 1;
        while (RentalUnit::where('unit_id', $candidateId)->exists()) {
            $candidateId = "{$building->building_id}-{$candidateUnitCode}_{$counter}";
            $counter++;
        }

        return $candidateId;
    }
}

