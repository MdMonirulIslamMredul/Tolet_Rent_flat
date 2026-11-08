<?php

namespace Database\Seeders;

use App\Models\Rental\Building;
use App\Models\Rental\RentalUnit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RentBDSadarSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Verified Demo Landlord / Owner
        $owner = User::updateOrCreate(['email' => 'owner@rentbd.com'], [
            'name' => 'হাজী রফিকুল ইসলাম',
            'password' => Hash::make('12345678'),
            'phone' => '01711000101',
            'phone_verified_at' => now(),
            'role' => 'owner',
            'nid_number' => '1985931234567',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'address' => 'আকুর টাকুর পাড়া, টাঙ্গাইল সদর',
        ]);

        // 2. Create Second Landlord
        $owner2 = User::updateOrCreate(['email' => 'owner2@rentbd.com'], [
            'name' => 'ইঞ্জি. মোশাররফ হোসেন',
            'password' => Hash::make('12345678'),
            'phone' => '01712000202',
            'phone_verified_at' => now(),
            'role' => 'owner',
            'nid_number' => '1982931234599',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'address' => 'ভিক্টোরিয়া রোড, টাঙ্গাইল সদর',
        ]);

        // 3. Create Verified Demo Tenant
        $tenant = User::updateOrCreate(['email' => 'tenant@rentbd.com'], [
            'name' => 'তানভীর আহমেদ',
            'password' => Hash::make('12345678'),
            'phone' => '01811000303',
            'phone_verified_at' => now(),
            'role' => 'tenant',
            'nid_number' => '1994931234568',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'address' => 'টাঙ্গাইল সদর',
        ]);

        // ----------------------------------------------------
        // Building 1: Swapnoneer Tower (Akur Takur Para)
        // ----------------------------------------------------
        $b1 = Building::updateOrCreate(['building_id' => 'BD-TNG-000101'], [
            'owner_id' => $owner->id,
            'name' => 'Swapnoneer Tower',
            'name_bn' => 'স্বপ্ননীড় টাওয়ার',
            'division' => 'Dhaka',
            'district' => 'Tangail',
            'district_code' => 'TNG',
            'thana_upazila' => 'Tangail Sadar',
            'area' => 'Akur Takur Para',
            'address' => 'হোল্ডিং নং ১২/এ, আকুর টাকুর পাড়া, ভিক্টোরিয়া রোড সংলগ্ন, টাঙ্গাইল সদর',
            'latitude' => 24.2520,
            'longitude' => 89.9175,
            'total_floors' => 6,
            'amenities' => [
                'লিফট (Lift)',
                'জেনারেটর ব্যাকআপ (Generator Backup)',
                'সিসিটিভি ক্যামেরা (CCTV Security)',
                'তিতাস গ্যাস লাইন (Gas Pipeline)',
                'ডিপ টিউবওয়েল পানি (24/7 Water)',
                'নিরাপত্তা প্রহরী (Security Guard 24/7)',
                'বাইক পার্কিং (Bike Parking)',
                'ছাদ ব্যবহারের সুবিধা (Rooftop Access)',
            ],
            'description' => 'টাঙ্গাইল শহরের অত্যন্ত শান্ত ও অভিজাত এলাকা আকুর টাকুর পাড়ায় অবস্থিত ৬ তলা আধুনিক আবাসিক ও বাণিজ্যিক ভবন। সার্বক্ষণিক লিফট, জেনারেটর ব্যাকআপ ও সার্বক্ষণিক নিরাপত্তার ব্যবস্থা রয়েছে।',
            'contact_person' => 'হাজী রফিকুল ইসলাম (বাড়িওয়ালা)',
            'contact_phone' => '01711-000101',
            'contact_whatsapp' => '01711000101',
            'is_featured' => true,
            'status' => 'active',
        ]);

        // Units for Building 1
        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000101-F3B'], [
            'building_id' => $b1->id,
            'category_type' => 'residential',
            'category' => 'flat',
            'title' => '৩য় তলা দক্ষিণমুখী ৩ বেডরুমের আলো-বাতাসপূর্ণ লাক্সারি ফ্ল্যাট',
            'unit_number' => '3B',
            'floor_level' => '3rd Floor',
            'size_sqft' => 1250,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'balconies' => 2,
            'facing' => 'South',
            'monthly_rent' => 14500.00,
            'service_charge' => 1500.00,
            'security_deposit' => 30000.00,
            'rent_negotiable' => true,
            'gas_type' => 'pipeline',
            'water_supply' => '24/7 Deep Tube-well',
            'electricity_type' => 'prepaid',
            'preferred_tenant' => 'family',
            'available_from' => now()->startOfMonth()->addMonth(),
            'is_vacant' => true,
            'status' => 'vacant',
            'amenities' => ['কিচেন কেবিনেট', 'গিজার পয়েন্ট', 'এসি পয়েন্ট', 'সম্পূর্ণ টাইলস করা মেঝ', 'বেলকনি'],
            'notes' => 'ছোট বা মাঝারি সাইজের মার্জিত পরিবারের জন্য অত্যন্ত উপযোগী।',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000101-F4A'], [
            'building_id' => $b1->id,
            'category_type' => 'residential',
            'category' => 'flat',
            'title' => '৪র্থ তলা পূর্বমুখী ২ বেডরুমের ফ্যামিলি ফ্ল্যাট',
            'unit_number' => '4A',
            'floor_level' => '4th Floor',
            'size_sqft' => 950,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'balconies' => 1,
            'facing' => 'East',
            'monthly_rent' => 11000.00,
            'service_charge' => 1200.00,
            'security_deposit' => 22000.00,
            'gas_type' => 'pipeline',
            'preferred_tenant' => 'family',
            'is_vacant' => false,
            'status' => 'occupied',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000101-S01'], [
            'building_id' => $b1->id,
            'category_type' => 'commercial',
            'category' => 'shop',
            'title' => 'গ্রাউন্ড ফ্লোর মেইন রোড ফেসিং কর্নার দোকান',
            'unit_number' => 'Shop-01',
            'floor_level' => 'Ground Floor',
            'size_sqft' => 220,
            'bedrooms' => null,
            'bathrooms' => 1,
            'monthly_rent' => 18000.00,
            'service_charge' => 500.00,
            'security_deposit' => 100000.00,
            'is_vacant' => true,
            'status' => 'vacant',
            'notes' => 'ফার্মেসি, ডিপার্টমেন্টাল স্টোর বা কসমেটিকস ব্যবসার জন্য সেরা অবস্থান।',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000101-G01'], [
            'building_id' => $b1->id,
            'category_type' => 'other',
            'category' => 'garage',
            'title' => 'সুরক্ষিত প্রাইভেট কার পার্কিং / গ্যারেজ স্পেস',
            'unit_number' => 'G-01',
            'floor_level' => 'Ground Floor',
            'size_sqft' => 150,
            'monthly_rent' => 2500.00,
            'is_vacant' => true,
            'status' => 'vacant',
        ]);

        $b1->recalculateCounts();

        // ----------------------------------------------------
        // Building 2: Green View Residency (Biswas Betka)
        // ----------------------------------------------------
        $b2 = Building::updateOrCreate(['building_id' => 'BD-TNG-000102'], [
            'owner_id' => $owner->id,
            'name' => 'Green View Residency',
            'name_bn' => 'গ্রিন ভিউ রেসিডেন্সি',
            'division' => 'Dhaka',
            'district' => 'Tangail',
            'district_code' => 'TNG',
            'thana_upazila' => 'Tangail Sadar',
            'area' => 'Biswas Betka',
            'address' => 'রোড নং ৩, বিশ্বাস বেতকা, নতুন বাসস্ট্যান্ড রোড, টাঙ্গাইল সদর',
            'latitude' => 24.2465,
            'longitude' => 89.9240,
            'total_floors' => 5,
            'amenities' => [
                'লিফট (Lift)',
                'সিসিটিভি (CCTV)',
                'সিলিন্ডার গ্যাস সুবিধা (Cylinder Gas)',
                '২৪ ঘণ্টা পানি (24/7 Water)',
                'গাড়ি পার্কিং (Car Parking)',
            ],
            'description' => 'টাঙ্গাইল নতুন বাসস্ট্যান্ডের কাছে খোলামেলা ও সবুজ মনোরম পরিবেশে তৈরি আধুনিক ফ্ল্যাট বাড়ি।',
            'contact_person' => 'মো: সুমন (কেয়ারটেকার)',
            'contact_phone' => '01722-000102',
            'is_featured' => false,
            'status' => 'active',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000102-F2A'], [
            'building_id' => $b2->id,
            'category_type' => 'residential',
            'category' => 'flat',
            'title' => '২য় তলা আধুনিক ২ বেডরুমের আলোছায়াময় ফ্ল্যাট',
            'unit_number' => '2A',
            'floor_level' => '2nd Floor',
            'size_sqft' => 900,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'balconies' => 2,
            'facing' => 'South-East',
            'monthly_rent' => 10500.00,
            'service_charge' => 1000.00,
            'security_deposit' => 20000.00,
            'gas_type' => 'cylinder',
            'preferred_tenant' => 'family',
            'is_vacant' => true,
            'status' => 'vacant',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000102-F5B'], [
            'building_id' => $b2->id,
            'category_type' => 'residential',
            'category' => 'flat',
            'title' => '৫ম তলা রুফটপ সংলগ্ন ৩ বেডরুম ফ্ল্যাট',
            'unit_number' => '5B',
            'floor_level' => '5th Floor',
            'size_sqft' => 1150,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'balconies' => 2,
            'facing' => 'South',
            'monthly_rent' => 13000.00,
            'service_charge' => 1200.00,
            'security_deposit' => 25000.00,
            'gas_type' => 'cylinder',
            'is_vacant' => true,
            'status' => 'vacant',
        ]);

        $b2->recalculateCounts();

        // ----------------------------------------------------
        // Building 3: Victoria Commercial Plaza (Commercial Hub)
        // ----------------------------------------------------
        $b3 = Building::updateOrCreate(['building_id' => 'BD-TNG-000103'], [
            'owner_id' => $owner2->id,
            'name' => 'Victoria Commercial Plaza',
            'name_bn' => 'ভিক্টোরিয়া কমার্শিয়াল প্লাজা',
            'division' => 'Dhaka',
            'district' => 'Tangail',
            'district_code' => 'TNG',
            'thana_upazila' => 'Tangail Sadar',
            'area' => 'Victoria Road',
            'address' => 'ভিক্টোরিয়া রোড, প্রধান বাণিজ্যিক এলাকা, টাঙ্গাইল সদর',
            'latitude' => 24.2495,
            'longitude' => 89.9160,
            'total_floors' => 4,
            'amenities' => [
                'লিফট (Lift)',
                'জেনারেটর ব্যাকআপ (Full Generator)',
                'সিসিটিভি ক্যামেরা (CCTV)',
                'ফায়ার সেফটি (Fire Safety)',
                'আলাদা কমার্শিয়াল মিটার (Separate Meter)',
            ],
            'description' => 'টাঙ্গাইল শহরের প্রাণকেন্দ্র ভিক্টোরিয়া রোডে অবস্থিত অত্যাধুনিক বাণিজ্যিক ভবন। ব্যাংক, শোরুম, ডায়াগনস্টিক বা করপোরেট অফিসের জন্য আদর্শ।',
            'contact_person' => 'ইঞ্জি. মোশাররফ হোসেন',
            'contact_phone' => '01712-000202',
            'is_featured' => true,
            'status' => 'active',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000103-S02'], [
            'building_id' => $b3->id,
            'category_type' => 'commercial',
            'category' => 'shop',
            'title' => '১ম তলা প্রাইম লোকেশন রেডি শোরুম / দোকান',
            'unit_number' => 'Shop-02',
            'floor_level' => '1st Floor',
            'size_sqft' => 350,
            'monthly_rent' => 25000.00,
            'security_deposit' => 200000.00,
            'is_vacant' => true,
            'status' => 'vacant',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000103-O01'], [
            'building_id' => $b3->id,
            'category_type' => 'commercial',
            'category' => 'office',
            'title' => '৩য় তলা করপোরেট অফিস বা ডায়াগনস্টিক স্পেস',
            'unit_number' => 'Office-301',
            'floor_level' => '3rd Floor',
            'size_sqft' => 800,
            'monthly_rent' => 32000.00,
            'security_deposit' => 150000.00,
            'is_vacant' => true,
            'status' => 'vacant',
        ]);

        $b3->recalculateCounts();

        // ----------------------------------------------------
        // Building 4: Rahman Manzil (College Mor)
        // ----------------------------------------------------
        $b4 = Building::updateOrCreate(['building_id' => 'BD-TNG-000104'], [
            'owner_id' => $owner->id,
            'name' => 'Rahman Manzil',
            'name_bn' => 'রহমান মঞ্জিল',
            'division' => 'Dhaka',
            'district' => 'Tangail',
            'district_code' => 'TNG',
            'thana_upazila' => 'Tangail Sadar',
            'area' => 'College Mor',
            'address' => 'সরকারি সা’দত কলেজ রোড, কলেজ মোড়, টাঙ্গাইল সদর',
            'latitude' => 24.2560,
            'longitude' => 89.9140,
            'total_floors' => 4,
            'amenities' => [
                'তিতাস গ্যাস লাইন',
                'ওয়াইফাই ইন্টারনেট (WiFi)',
                '২৪ ঘণ্টা পানি',
                'বাইক পার্কিং',
            ],
            'description' => 'কলেজ ও বিশ্ববিদ্যালয়ের শিক্ষার্থী এবং পরিবারদের জন্য সুলভ মূল্যে পরিষ্কার-পরিচ্ছন্ন পরিবেশের বাসা ও মেস।',
            'contact_person' => 'হাজী রফিকুল ইসলাম',
            'contact_phone' => '01711-000101',
            'is_featured' => false,
            'status' => 'active',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000104-M01'], [
            'building_id' => $b4->id,
            'category_type' => 'residential',
            'category' => 'mess',
            'title' => 'ছাত্র / চাকুরিজীবী ব্যাচেলর সিঙ্গেল রুম',
            'unit_number' => 'Room-3A',
            'floor_level' => '3rd Floor',
            'size_sqft' => 160,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'monthly_rent' => 3500.00,
            'security_deposit' => 3500.00,
            'preferred_tenant' => 'bachelor',
            'is_vacant' => true,
            'status' => 'vacant',
        ]);

        RentalUnit::updateOrCreate(['unit_id' => 'BD-TNG-000104-F2B'], [
            'building_id' => $b4->id,
            'category_type' => 'residential',
            'category' => 'flat',
            'title' => '২য় তলা ২ বেডরুম ফ্যামিলি ফ্ল্যাট',
            'unit_number' => '2B',
            'floor_level' => '2nd Floor',
            'size_sqft' => 850,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'monthly_rent' => 9000.00,
            'is_vacant' => false,
            'status' => 'occupied',
        ]);

        $b4->recalculateCounts();
    }
}

