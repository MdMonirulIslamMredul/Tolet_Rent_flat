<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class SampleContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::truncate();

        Contact::create([
            'name' => 'আব্দুর রহিম',
            'email' => 'rahim@example.com',
            'phone' => '01712-345678',
            'subject' => 'ভবনের গেটের জন্য কিউআর পোস্টার অর্ডার',
            'message' => "আসসালামু আলাইকুম,\n\nআমার আকুর টাকুর পাড়ায় একটি ৫ তলা ভবন রয়েছে। আমি গেটের জন্য ওয়াটারপ্রুফ কিউআর পোস্টার লাগাতে চাই। কিভাবে পাওয়া যাবে জানাবেন।\n\nধন্যবাদ,\nআব্দুর রহিম",
            'read_at' => null, // Unread
            'is_connected' => false,
            'connected_at' => null,
            'created_at' => now()->subHours(2),
        ]);

        Contact::create([
            'name' => 'মাহমুদুল হাসান',
            'email' => 'hasan@example.com',
            'phone' => '01811-987654',
            'subject' => 'টাঙ্গাইল সদর বাজারে দোকান ভাড়া সংক্রান্ত তথ্য',
            'message' => "ভিক্টোরিয়া কমার্শিয়াল প্লাজায় কোনো শোরুম স্পেস খালি আছে কি না জানতে চাই। অগ্রিম জামানত কত দিতে হবে?\n\nমাহমুদুল হাসান",
            'read_at' => null, // Unread
            'is_connected' => true,
            'connected_at' => now()->subHour(),
            'admin_notes' => 'মালিকের সাথে যোগাযোগ করিয়ে দেওয়া হয়েছে।',
            'created_at' => now()->subHours(5),
        ]);

        Contact::create([
            'name' => 'সুলতানা পারভীন',
            'email' => 'sultana@example.com',
            'phone' => '01911-456789',
            'subject' => 'ডিজিটাল ভাড়া চুক্তিপত্র সেবা সম্পর্কে অনুসন্ধান',
            'message' => "আমাদের বর্তমান ভাড়াটিয়ার সাথে অনলাইনে ডিজিটাল চুক্তিপত্র সম্পাদন করতে চাই। প্রক্রিয়াটি জানতে চাচ্ছি।",
            'read_at' => now()->subDays(1),
            'is_connected' => true,
            'connected_at' => now()->subHours(18),
            'admin_notes' => 'চুক্তিপত্রের নিয়মাবলী ও লিংক পাঠানো হয়েছে।',
            'created_at' => now()->subDays(1),
        ]);
    }
}
