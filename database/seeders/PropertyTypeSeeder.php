<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Flat',
                'name_bn' => 'ফ্ল্যাট',
                'base_price' => 12000.00,
                'icon' => 'bi-building',
                'description' => 'ফ্যামিলি ফ্ল্যাট ও আধুনিক অ্যাপার্টমেন্ট',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Independent House',
                'name_bn' => 'সম্পূর্ণ বাসা',
                'base_price' => 20000.00,
                'icon' => 'bi-house-door',
                'description' => 'একক বাড়ি বা সম্পূর্ণ ফ্লোর বাসা ভাড়া',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sublet',
                'name_bn' => 'সাবলেট',
                'base_price' => 5000.00,
                'icon' => 'bi-door-closed',
                'description' => 'ফ্যামিলি বা কর্মজীবীদের জন্য একক রুম সাবলেট',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Mess & Hostel',
                'name_bn' => 'মেস ও হোস্টেল',
                'base_price' => 3000.00,
                'icon' => 'bi-people',
                'description' => 'শিক্ষার্থী ও চাকরিজীবীদের জন্য ব্যাচেলর রুম ও মেস সিট',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Retail Shop',
                'name_bn' => 'দোকান',
                'base_price' => 15000.00,
                'icon' => 'bi-shop',
                'description' => 'বাজার, প্লাজা বা রাস্তার পাশের বাণিজ্যিক দোকান',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Commercial Office',
                'name_bn' => 'অফিস স্পেস',
                'base_price' => 25000.00,
                'icon' => 'bi-briefcase',
                'description' => 'করপোরেট অফিস, চেম্বার ও কনসালটেন্সি স্পেস',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Garage & Parking',
                'name_bn' => 'গ্যারেজ ও পার্কিং',
                'base_price' => 2500.00,
                'icon' => 'bi-car-front',
                'description' => 'কার ও মোটরসাইকেল নিরাপদ পার্কিং ও গ্যারেজ স্পেস',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Rooftop & Land',
                'name_bn' => 'ছাদ ও জমি লিজ',
                'base_price' => 10000.00,
                'icon' => 'bi-layers',
                'description' => 'ছাদ বাগান/রেস্তোরাঁ ও বাণিজ্যিক জমি লিজ',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            PropertyType::updateOrCreate(
                ['slug' => Str::slug($type['name'])],
                array_merge($type, ['slug' => Str::slug($type['name'])])
            );
        }
    }
}
