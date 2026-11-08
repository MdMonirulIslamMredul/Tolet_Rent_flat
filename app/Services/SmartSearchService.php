<?php

namespace App\Services;

class SmartSearchService
{
    /**
     * Map Bengali numerals to English
     */
    protected static function bn2enDigits(string $str): string
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($bn, $en, $str);
    }

    /**
     * Parse natural language search prompt
     */
    public static function parse(string $query): array
    {
        $raw = trim($query);
        $normalized = self::bn2enDigits(mb_strtolower($raw));

        $criteria = [
            'original_query' => $raw,
            'district' => null,
            'thana_upazila' => null,
            'area' => null,
            'category' => null,
            'category_type' => null,
            'bedrooms' => null,
            'min_rent' => null,
            'max_rent' => null,
            'preferred_tenant' => null,
            'keywords' => [],
        ];

        // 1. Detect Location (District / Thana / Area)
        if (str_contains($normalized, 'টাঙ্গাইল') || str_contains($normalized, 'tangail')) {
            $criteria['district'] = 'Tangail';
            if (str_contains($normalized, 'সদর') || str_contains($normalized, 'sadar')) {
                $criteria['thana_upazila'] = 'Tangail Sadar';
            }
        } elseif (str_contains($normalized, 'ঢাকা') || str_contains($normalized, 'dhaka')) {
            $criteria['district'] = 'Dhaka';
        } elseif (str_contains($normalized, 'গাজীপুর') || str_contains($normalized, 'gazipur')) {
            $criteria['district'] = 'Gazipur';
        }

        // Tangail specific areas
        $areas = [
            'আকুর টাকুর' => 'Akur Takur Para',
            'akur takur' => 'Akur Takur Para',
            'বিশ্বাস বেতকা' => 'Biswas Betka',
            'biswas betka' => 'Biswas Betka',
            'কলেজ মোড়' => 'College Mor',
            'কলেজ মোড়' => 'College Mor',
            'college mor' => 'College Mor',
            'সাবালিয়া' => 'Sabalia',
            'সাবালিয়া' => 'Sabalia',
            'sabalia' => 'Sabalia',
            'আদালত পাড়া' => 'Adalat Para',
            'আদালত পাড়া' => 'Adalat Para',
            'ভিক্টোরিয়া' => 'Victoria Road',
            'ভিক্টোরিয়া' => 'Victoria Road',
            'victoria' => 'Victoria Road',
            'মিরপুর' => 'Mirpur',
            'mirpur' => 'Mirpur',
            'ধানমন্ডি' => 'Dhanmondi',
            'উত্তরা' => 'Uttara',
        ];

        foreach ($areas as $keyword => $areaName) {
            if (str_contains($normalized, $keyword)) {
                $criteria['area'] = $areaName;
                if (!isset($criteria['district']) || !$criteria['district']) {
                    $criteria['district'] = in_array($areaName, ['Mirpur', 'Dhanmondi', 'Uttara']) ? 'Dhaka' : 'Tangail';
                }
                break;
            }
        }

        // 2. Detect Category
        $categories = [
            'ফ্ল্যাট' => 'flat',
            'flat' => 'flat',
            'অ্যাপার্টমেন্ট' => 'flat',
            'বাসা' => 'house',
            'house' => 'house',
            'বাড়ি' => 'house',
            'দোকান' => 'shop',
            'shop' => 'shop',
            'অফিস' => 'office',
            'office' => 'office',
            'শোরুম' => 'showroom',
            'showroom' => 'showroom',
            'গুদাম' => 'warehouse',
            'গোডাউন' => 'warehouse',
            'warehouse' => 'warehouse',
            'গ্যারেজ' => 'garage',
            'garage' => 'garage',
            'পার্কিং' => 'parking',
            'parking' => 'parking',
            'ছাদ' => 'roof',
            'roof' => 'roof',
            'মেস' => 'mess',
            'mess' => 'mess',
            'সাবলেট' => 'sublet',
            'sublet' => 'sublet',
            'হোস্টেল' => 'hostel',
            'hostel' => 'hostel',
        ];

        foreach ($categories as $keyword => $category) {
            if (str_contains($normalized, $keyword)) {
                $criteria['category'] = $category;
                $criteria['category_type'] = in_array($category, ['shop', 'office', 'showroom', 'warehouse', 'factory'])
                    ? 'commercial'
                    : (in_array($category, ['garage', 'parking', 'roof', 'land_lease']) ? 'other' : 'residential');
                break;
            }
        }

        // 3. Detect Bedrooms
        $bedroomWords = [
            '১ বেড' => 1, 'এক বেড' => 1, '1 bed' => 1, '1 bedroom' => 1,
            '২ বেড' => 2, 'দুই বেড' => 2, '2 bed' => 2, '2 bedroom' => 2, 'দু বেড' => 2,
            '৩ বেড' => 3, 'তিন বেড' => 3, '3 bed' => 3, '3 bedroom' => 3,
            '৪ বেড' => 4, 'চার বেড' => 4, '4 bed' => 4, '4 bedroom' => 4,
            '৫ বেড' => 5, 'পাঁচ বেড' => 5, '5 bed' => 5,
        ];

        foreach ($bedroomWords as $word => $count) {
            if (str_contains($normalized, $word)) {
                $criteria['bedrooms'] = $count;
                break;
            }
        }

        // Also regex check for digits followed by bed: e.g. "2 bed", "3 bed"
        if (!$criteria['bedrooms'] && preg_match('/(\d+)\s*(?:bed|বেড)/u', $normalized, $m)) {
            $criteria['bedrooms'] = (int) $m[1];
        }

        // 4. Detect Preferred Tenant
        if (str_contains($normalized, 'ফ্যামিলি') || str_contains($normalized, 'family')) {
            $criteria['preferred_tenant'] = 'family';
        } elseif (str_contains($normalized, 'ব্যাচেলর') || str_contains($normalized, 'bachelor')) {
            $criteria['preferred_tenant'] = 'bachelor';
        }

        // 5. Detect Budget / Rent range
        // Check for "X থেকে Y হাজার" (e.g. "10 থেকে 15 হাজার" or "10k - 15k")
        if (preg_match('/(\d+)\s*(?:থেকে|to|-)\s*(\d+)\s*(?:হাজার|k)?/u', $normalized, $matches)) {
            $val1 = (int) $matches[1];
            $val2 = (int) $matches[2];
            $mult = (str_contains($normalized, 'হাজার') || str_contains($normalized, 'k') || $val1 < 100) ? 1000 : 1;
            $criteria['min_rent'] = $val1 * $mult;
            $criteria['max_rent'] = $val2 * $mult;
        }
        // Check for "X হাজার টাকার মধ্যে" or "under X thousand" or "X k"
        elseif (preg_match('/(?:under|below|সর্বোচ্চ|পর্যন্ত)?\s*(\d+)\s*(?:হাজার|k)\s*(?:টাকার\s*মধ্যে)?/u', $normalized, $matches)
            || preg_match('/(\d+)\s*(?:হাজার|k)?\s*টাকার\s*মধ্যে/u', $normalized, $matches)) {
            $val = (int) $matches[1];
            $mult = ($val < 100) ? 1000 : 1;
            $criteria['max_rent'] = $val * $mult;
        }
        // Check for direct numeric thousands e.g. "15000"
        elseif (preg_match('/(?:মধ্যে|under|পর্যন্ত|বাজেট|budget)?\s*(\d{4,6})/u', $normalized, $matches)) {
            $criteria['max_rent'] = (int) $matches[1];
        }

        return $criteria;
    }
}

