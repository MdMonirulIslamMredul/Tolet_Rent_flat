<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Tangail Real Estate Forum',
                'image' => 'partners/forum.png',
                'website_url' => 'https://rentbd.com',
                'description' => 'টাঙ্গাইল জেলা রিয়েল এস্টেট ও বাড়িওয়ালা কল্যাণ সমিতি। নিরাপদ ও নিয়মমাফিক ভাড়া পরিচালনায় সহযোগী।',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Smart Property Bangladesh',
                'image' => 'partners/smart.png',
                'website_url' => 'https://rentbd.com',
                'description' => 'বাংলাদেশের প্রোপটেক ইকোসিস্টেম ও স্মার্ট প্রপার্টি ম্যানেজমেন্ট রিসার্চ নেটওয়ার্ক।',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Tenancy Trust & Legal Network',
                'image' => 'partners/trust.png',
                'website_url' => 'https://rentbd.com',
                'description' => 'বাড়ি ভাড়া আইন, বিরোধ নিষ্পত্তি এবং ডিজিটাল চুক্তিপত্র বাস্তবায়ন সহায়ক সংস্থা।',
                'sort_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Verified Tenant Assurance BD',
                'image' => 'partners/assurance.png',
                'website_url' => 'https://rentbd.com',
                'description' => 'জাতীয় পরিচয়পত্র ও পুলিশ তথ্য যাচাইকরণ সহযোগী নেটওয়ার্ক।',
                'sort_order' => 4,
                'status' => true,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(['name' => $partner['name']], $partner);
        }
    }
}
