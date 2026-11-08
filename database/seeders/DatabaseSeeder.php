<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Blog;
use App\Models\Counter;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\HomepageCarousel;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Admin User & Spatie Roles
        $adminUser = User::updateOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin User',
            'password' => '12345678',
            'is_admin' => true,
            'role' => 'admin',
        ]);

        $permissions = [
            'access admin panel',
            'view',
            'create',
            'edit',
            'delete',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage products',
            'manage services',
            'manage team members',
            'manage blogs',
            'manage galleries',
            'manage contacts',
            'Web_Settings',
            'manage branches',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $superAdminRole->syncPermissions($permissions);
        $adminRole->syncPermissions($permissions);
        $editorRole->syncPermissions(['access admin panel', 'view', 'create', 'edit']);
        $userRole->syncPermissions(['view']);

        $adminUser->assignRole($superAdminRole);

        // Remove legacy solar data if any exists
        Service::where('title', 'like', '%solar%')->delete();
        Product::where('slug', 'like', '%solar%')->delete();
        Blog::where('slug', 'like', '%solar%')->delete();

        // 2. RentBD Global Settings
        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'RentBD',
            'logo_path' => 'settings/logo.png',
            'favicon_path' => 'settings/favicon.png',
            'meta_title' => 'RentBD – Smart QR Based Rental Platform | বাসা ও দোকান ভাড়া',
            'meta_description' => 'বাংলাদেশের প্রথম স্মার্ট কিউআর কোড ভিত্তিক রেন্টাল প্ল্যাটফর্ম। রিয়েল-টাইম খালি ফ্ল্যাট, বাসা, দোকান ও গ্যারেজ খুঁজুন এবং বাড়িওয়ালা ও ভাড়াটিয়াকে সরাসরি সংযুক্ত করুন।',
            'seo_keywords' => 'rentbd, tolet, বাসা ভাড়া, ফ্ল্যাট ভাড়া, দোকান ভাড়া, qr tolet, smart rental platform, tangail rental, dhaka rent',
            'hero_title' => 'স্মার্ট কিউআর কোডে রিয়েল-টাইম বাসা ও দোকান ভাড়া',
            'hero_subtitle' => 'গেটের কিউআর কোড স্ক্যান করেই জানুন কোন ফ্ল্যাট খালি আছে। AI সার্চ, লাইভ ভ্যাকেন্সি ম্যাপ ও ডিজিটাল চুক্তির মাধ্যমে দ্রুত ও নিরাপদ ভাড়া সেবা।',
            'company_intro' => 'RentBD বাংলাদেশের ভাড়া ব্যবস্থাকে ডিজিটাল, স্বচ্ছ ও দ্রুত করতে নিয়ে এসেছে Smart QR Code, Permanent Property ID এবং Verified User Ecosystem।',
            'cta_title' => 'আপনার বাড়ি বা বাণিজ্যিক ভবনকে দিন ডিজিটাল পরিচয়',
            'cta_text' => 'আজই আপনার ভবনে RentBD Smart QR পোস্টার লাগিয়ে খালি ফ্ল্যাট ও দোকানের রিয়েল-টাইম তথ্য দিন এবং যাচাইকৃত ভাড়াটিয়া পান।',
            'cta_button_text' => 'বাড়ি যোগ করুন (Add Building)',
            'cta_button_link' => '/owner/buildings/create',
            'about_title' => 'RentBD সম্পর্কে (About RentBD)',
            'about_content' => 'RentBD একটি আধুনিক রেন্টাল প্ল্যাটফর্ম যা কিউআর কোড, পার্মানেন্ট প্রপার্টি আইডি এবং এআই সার্চের মাধ্যমে বাড়ির মালিক ও ভাড়াটিয়াকে সরাসরি সংযুক্ত করে।',
            'mission' => 'বাংলাদেশের প্রতিটি ভাড়ার সম্পত্তিকে একটি স্থায়ী ডিজিটাল পরিচয় দিয়ে একটি নিরাপদ, স্বচ্ছ ও নির্ভরযোগ্য রেন্টাল ইকোসিস্টেম গড়ে তোলা।',
            'vision' => 'দেশের এক নম্বর স্মার্ট রেন্টাল প্ল্যাটফর্ম হওয়া, যেখানে কিউআর কোড ও প্রযুক্তির মাধ্যমে বাসা খোঁজা ও ভাড়া পরিচালনা সম্পূর্ণ পেপারলেস হবে।',
            'history' => 'টাঙ্গাইল সদর থেকে যাত্রা শুরু করে সারা বাংলাদেশে রেন্টাল খাতের আধুনিকায়নে RentBD কাজ করে যাচ্ছে।',
            'contact_email' => 'info@rentbd.com',
            'contact_phone' => '+880 1711-000101',
            'contact_address' => 'ভিক্টোরিয়া রোড, আকুর টাকুর পাড়া, টাঙ্গাইল সদর, বাংলাদেশ',
            'google_map_embed' => 'https://maps.google.com/maps?q=Tangail&t=&z=14&ie=UTF8&iwloc=&output=embed',
            'social_links' => [
                'facebook' => 'https://facebook.com/rentbd',
                'linkedin' => 'https://linkedin.com/company/rentbd',
                'youtube' => 'https://youtube.com/@rentbd',
            ],
            'primary_color' => '#0d6efd',
            'secondary_color' => '#6c757d',
            'accent_color' => '#f39c12',
            'text_color' => '#0f172a',
            'bg_color' => '#ffffff',
        ]);

        // 3. RentBD About Us
        About::updateOrCreate(['id' => 1], [
            'title' => 'RentBD – Smart QR Based Rental Ecosystem',
            'page_details' => '<p>RentBD বাংলাদেশের প্রথম ও একমাত্র কিউআর কোড ও ইউনিক প্রপার্টি আইডি ভিত্তিক স্মার্ট রেন্টাল প্ল্যাটফর্ম। আমাদের মূল লক্ষ্য বাড়ির মালিক এবং ভাড়াটিয়ার মধ্যকার দূরত্ব ঘুচিয়ে একটি স্বচ্ছ, দ্রুত এবং নিরাপদ ভাড়া ব্যবস্থা উপহার দেওয়া।</p>',
            'banner_image' => 'abouts/about_banner.jpg',
            'details1' => '<p>প্রতিটি ভবনের জন্য পার্মানেন্ট Building ID (যেমন: BD-TNG-000101) ও প্রিন্টযোগ্য গেট পোস্টার প্রদান, যা স্ক্যান করলেই তাৎক্ষণিক খালি ফ্ল্যাট ও দোকানের তালিকা পাওয়া যায়।</p>',
            'image1' => 'abouts/about_image1.jpg',
            'details2' => '<p>লাইভ ভ্যাকেন্সি ম্যাপ ভিউয়ের মাধ্যমে শহরের কোথায় কোথায় ভাড়ার ইউনিট খালি আছে তা সবুজ মার্কার দিয়ে এক নজরে ম্যাপে দেখা যায়।</p>',
            'image2' => 'abouts/about_image2.jpg',
            'details3' => '<p>ন্যাচারাল ল্যাঙ্গুয়েজ AI সার্চ—যেখানে সরাসরি নিজের ভাষায় লিখে বাজেট ও রুম অনুযায়ী তাৎক্ষণিক সার্চ ফিল্টার পাওয়া সম্ভব।</p>',
            'details4' => '<p>জাতীয় পরিচয়পত্র (NID) ও সেলফি ফেস ম্যাচিংয়ের মাধ্যমে শতভাগ ভেরিফায়েড ব্যবহারকারী এবং আইনগতভাবে সুরক্ষিত ডিজিটাল চুক্তিপত্র (Digital Agreement)।</p>',
            'mission' => 'বাংলাদেশের প্রতিটি ভাড়ার সম্পত্তিকে একটি স্থায়ী ডিজিটাল পরিচয় দিয়ে একটি নিরাপদ, স্বচ্ছ ও নির্ভরযোগ্য রেন্টাল ইকোসিস্টেম গড়ে তোলা।',
            'vision' => 'দেশের এক নম্বর স্মার্ট রেন্টাল প্ল্যাটফর্ম হওয়া, যেখানে কিউআর কোড ও প্রযুক্তির মাধ্যমে বাসা খোঁজা ও ভাড়া পরিচালনা সম্পূর্ণ পেপারলেস হবে।',
            'history' => 'টাঙ্গাইল সদর থেকে যাত্রা শুরু করে সারা বাংলাদেশে রেন্টাল খাতের আধুনিকায়নে RentBD কাজ করে যাচ্ছে। প্রতিটি বিল্ডিংয়ের গেটে কিউআর কোড স্থাপন ও ডিজিটাল চুক্তিপত্রের মাধ্যমে ভাড়াটিয়া ও বাড়িওয়ালার বিশ্বাস অর্জন আমাদের মূল চালিকাশক্তি।',
            'key_values' => [
                'ডিজিটাল স্বচ্ছতা (Transparency)',
                'স্মার্ট কিউআর প্রযুক্তি (Smart QR)',
                'নিরাপত্তা ও বিশ্বাস (Trust & Verification)',
                'পেপারলেস ইকোসিস্টেম (Paperless Tenancy)',
            ],
            'years_experience' => 5,
            'establishment_year' => 2021,
        ]);

        // 4. RentBD Homepage Carousels
        HomepageCarousel::truncate();
        HomepageCarousel::create([
            'title' => 'RentBD – স্মার্ট কিউআর ভিত্তিক রেন্টাল প্ল্যাটফর্ম',
            'subtitle' => 'ভবনের গেটের কিউআর কোড স্ক্যান করেই মুহূর্তেই জানুন কোন ইউনিট খালি আছে, ভাড়া কত ও সরাসরি আবেদন করুন।',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        HomepageCarousel::create([
            'title' => 'গুগল ও লাইভ ভ্যাকেন্সি ম্যাপ',
            'subtitle' => 'সবুজ ও লাল মার্কারের মাধ্যমে টাঙ্গাইল সদর সহ শহরের সমস্ত খালি বাসা ও দোকান এক নজরে ম্যাপে খুঁজুন।',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        HomepageCarousel::create([
            'title' => 'যাচাইকৃত ব্যবহারকারী ও ডিজিটাল ভাড়া চুক্তিপত্র',
            'subtitle' => 'NID ও সেলফি ভেরিফিকেশনের মাধ্যমে জালিয়াতিমুক্ত পরিবেশ এবং স্ট্যান্ডার্ড ই-স্বাক্ষরিত চুক্তিপত্র প্রস্তুত।',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 5. RentBD Rental Services
        Service::truncate();
        $rentbdServices = [
            [
                'title' => 'স্মার্ট কিউআর গেট পোস্টার সেবা',
                'slug' => 'smart-qr-gate-poster',
                'short_description' => 'প্রতিটি বাড়ির গেটের জন্য আকর্ষণীয় ও টেকসই কিউআর পোস্টার ডিজাইন ও প্রিন্ট ডেলিভারি।',
                'description' => 'আপনার ভবনের স্থায়ী Building ID এবং ইউনিক QR Code সংবলিত লেমিনেটেড ওয়াটারপ্রুফ পোস্টার আপনার ভবনের গেটে লাগানোর জন্য প্রস্তুত করে দেওয়া হয়।',
                'price' => 500.00,
                'price_range' => '৳৫০০ - ৳১,০০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-qr-code-scan',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'যাচাইকৃত ভাড়াটিয়া ম্যাচিং (Tenant Screening)',
                'slug' => 'verified-tenant-screening',
                'short_description' => 'NID নম্বর, ওটিপি এবং সেলফি ফেস ম্যাচিংয়ের মাধ্যমে বিশ্বস্ত ভাড়াটিয়া বাছাই।',
                'description' => 'ভাড়া দেওয়ার আগে ভাড়াটিয়ার জাতীয় পরিচয়পত্র, পেশাগত ব্যাকগ্রাউন্ড ও পুলিশ ভেরিফিকেশন সহায়তা।',
                'price' => 800.00,
                'price_range' => '৳৮০০ - ৳১,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-shield-check',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'ডিজিটাল ভাড়া চুক্তিপত্র (Digital Agreement)',
                'slug' => 'digital-tenancy-agreement',
                'short_description' => 'বাংলাদেশি আইনের ধারা অনুযায়ী স্ট্যান্ডার্ড ও পেপারলেস ডিজিটাল চুক্তিপত্র প্রস্তুত।',
                'description' => 'ভাড়াটিয়া ও মালিকের মধ্যে শর্তাবলী, অগ্রিম জামানত ও নোটিশ সংক্রান্ত আইনগতভাবে সুরক্ষিত ডিজিটাল চুক্তিপত্র।',
                'price' => 1200.00,
                'price_range' => '৳১,২০০ - ৳২,০০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-file-earmark-text',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'রিয়েল-টাইম ভ্যাকেন্সি ও প্রপার্টি ম্যানেজমেন্ট',
                'slug' => 'realtime-vacancy-property-management',
                'short_description' => 'বাড়িওয়ালাদের জন্য ফ্ল্যাট/দোকান ব্যবস্থাপনা, ভাড়া সংগ্রহ ট্র্যাকিং ও নোটিফিকেশন।',
                'description' => '১-ক্লিকে ভ্যাকেন্সি অন/অফ, ভাড়া সংগ্রহের রেকর্ড এবং ভাড়াটিয়া কমিউনিকেশন ড্যাশবোর্ড।',
                'price' => 1500.00,
                'price_range' => '৳১,৫০০ - ৳৩,০০০/মাস',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-building',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'ফটোগ্রাফি ও ভার্চুয়াল ফ্ল্যাট ট্যুর',
                'slug' => 'property-photography-virtual-tour',
                'short_description' => 'ফ্ল্যাট ও বাণিজ্যিক স্পেসের প্রফেশনাল এইচডি ছবি ও ভিডিও ট্যুর প্রস্তুতকরণ।',
                'description' => 'আকর্ষণীয় প্রেজেন্টেশনের মাধ্যমে ঘরে বসেই ভাড়াটিয়াদের পূর্ণাঙ্গ ফ্ল্যাট দেখানোর সুযোগ।',
                'price' => 2000.00,
                'price_range' => '৳২,০০০ - ৳৪,০০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-camera',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'আইনি পরামর্শ ও নোটিশ ব্যবস্থাপনা',
                'slug' => 'rental-legal-notice-support',
                'short_description' => 'বাড়ি ভাড়া আইন ও বিরোধ নিষ্পত্তিতে অভিজ্ঞ আইন বিশেষজ্ঞদের সহায়তা।',
                'description' => 'ভাড়া বকেয়া, ফ্ল্যাট ছাড়া বা বিরোধ সংক্রান্ত যেকোনো আইনি নোটিশ প্রেরণ ও পরামর্শ প্রদান।',
                'price' => 2500.00,
                'price_range' => '৳২,৫০০ - ৳৫,০০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-briefcase',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($rentbdServices as $serviceData) {
            Service::create($serviceData);
        }

        // 6. RentBD Blogs
        Blog::truncate();
        $rentbdBlogs = [
            [
                'title' => 'কিউআর কোড দিয়ে কিভাবে সহজেই বাসা ও দোকান ভাড়া খুঁজবেন?',
                'slug' => 'how-to-find-rentals-using-qr-code',
                'excerpt' => 'রাস্তায় হেঁটে হেঁটে টু-লেট খোঁজার দিন শেষ। এখন যেকোনো ভবনের গেটের কিউআর কোড স্ক্যান করেই জানতে পারবেন কোন ফ্ল্যাট খালি আছে।',
                'body' => '<p>বাংলাদেশে বাসা খোঁজা সবসময়ই একটি সময়সাপেক্ষ ও ক্লান্তিকর প্রক্রিয়া। কিন্তু RentBD-এর Smart QR প্রযুক্তির মাধ্যমে এখন বাড়ির দরজায় দাঁড়িয়ে স্মার্টফোনের ক্যামেরা দিয়ে কিউআর স্ক্যান করলেই তাৎক্ষণিক ভবনের খালি ইউনিট, ভাড়া, বেডরুম ও বাথরুমের সংখ্যা জানা যায়।</p><p>এতে করে বারবার বাড়িওয়ালাকে ফোন দিয়ে বিরক্ত করতে হয় না এবং ভাড়াটিয়া নিজের সুবিধামতো সিদ্ধান্ত নিতে পারেন।</p>',
                'meta_title' => 'কিউআর কোড দিয়ে বাসা ও দোকান ভাড়া খোঁজার উপায় | RentBD',
                'meta_description' => 'কিউআর কোড স্ক্যান করে কিভাবে মুহূর্তেই খালি ফ্ল্যাটের তথ্য পাওয়া যায় তা বিস্তারিত জানুন।',
                'seo_keywords' => 'qr rental, tolet, বাসা ভাড়া, ফ্ল্যাট ভাড়া, rentbd',
                'published_at' => now()->subDays(2),
                'is_published' => true,
            ],
            [
                'title' => 'বাড়ি ভাড়া চুক্তিপত্র করার সঠিক নিয়ম ও জরুরি শর্তাবলী',
                'slug' => 'rules-and-terms-of-tenancy-agreement',
                'excerpt' => 'বাড়ি ভাড়া নেওয়ার আগে লিখিত ও আইনগত চুক্তিপত্র করা কেন জরুরি এবং কোন কোন শর্তাবলী থাকা উচিত।',
                'body' => '<p>ভাড়াটিয়া এবং বাড়িওয়ালার মধ্যে সুসম্পর্ক বজায় রাখতে এবং অনাকাঙ্ক্ষিত বিরোধ এড়াতে একটি স্পষ্ট চুক্তিপত্র থাকা অত্যন্ত আবশ্যক। এতে মাসিক ভাড়া, অগ্রিম জামানতের পরিমাণ, ইউটিলিটি বিলের দায়িত্ব এবং বাসা ছাড়ার নোটিশ পিরিয়ড স্পষ্টভাবে উল্লেখ থাকা প্রয়োজন।</p>',
                'meta_title' => 'বাড়ি ভাড়া চুক্তিপত্রের নিয়মাবলী | RentBD',
                'meta_description' => 'বাড়ি ভাড়া চুক্তি করার সঠিক নিয়ম ও গুরুত্বপূর্ণ শর্তাবলী জেনে নিন।',
                'seo_keywords' => 'tenancy agreement, চুক্তিপত্র, বাড়ি ভাড়া আইন',
                'published_at' => now()->subDays(4),
                'is_published' => true,
            ],
            [
                'title' => 'ভাড়া নেওয়ার আগে ফ্ল্যাটের যে বিষয়গুলো অবশ্যই যাচাই করবেন',
                'slug' => 'things-to-check-before-renting-a-flat',
                'excerpt' => 'নতুন বাসায় ওঠার আগে গ্যাস লাইন, পানির চাপ, বিদ্যুৎ মিটার ও নিরাপত্তা নিশ্চিত করার দরকারি টিপস।',
                'body' => '<p>বাসা পছন্দ হওয়ার পর অগ্রিম দেওয়ার আগে নিশ্চিত হোন গ্যাস লাইন তিতাস নাকি সিলিন্ডার, প্রিপেইড মিটারে কোনো বকেয়া আছে কি না এবং লিফট ও জেনারেটর ব্যাকআপ ২৪ ঘণ্টা সচল থাকে কি না।</p>',
                'meta_title' => 'ফ্ল্যাট ভাড়া নেওয়ার জরুরি চেকলিস্ট | RentBD',
                'meta_description' => 'ফ্ল্যাট বা বাসা ভাড়া নেওয়ার আগে করণীয় বিষয়সমূহ।',
                'seo_keywords' => 'flat rental checklist, বাসা ভাড়া টিপস',
                'published_at' => now()->subDays(6),
                'is_published' => true,
            ],
        ];

        foreach ($rentbdBlogs as $blogData) {
            Blog::create($blogData);
        }

        // 7. RentBD Team Members
        TeamMember::truncate();
        $team = [
            [
                'name' => 'ইঞ্জি. মোশাররফ হোসেন',
                'designation' => 'Chief Technology Officer (PropTech)',
                'bio' => 'সফটওয়্যার আর্কিটেক্ট ও প্রোপটেক গবেষক। বাংলাদেশে স্মার্ট কিউআর রেন্টাল ইকোসিস্টেমের প্রযুক্তিগত রূপকার।',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'এডভোকেট নাজমুল হাসান',
                'designation' => 'Legal & Tenancy Advisor',
                'bio' => 'বাংলাদেশ সুপ্রিম কোর্টের আইনজীবী ও প্রপার্টি ল বিশেষজ্ঞ। ডিজিটাল চুক্তিপত্র ও আইনি কাঠামোর উপদেষ্টা।',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'তানজিম আহমেদ',
                'designation' => 'Head of Property Operations',
                'bio' => 'বাড়িওয়ালাদের অনবোর্ডিং, কিউআর পোস্টার বাস্তবায়ন ও টাঙ্গাইল আঞ্চলিক অপারেশন্স প্রধান।',
                'status' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($team as $member) {
            TeamMember::create($member);
        }

        // 8. RentBD Testimonials
        Testimonial::truncate();
        $testimonials = [
            [
                'name' => 'হাজী রফিকুল ইসলাম',
                'designation' => 'বাড়ির মালিক (স্বপ্ননীড় টাওয়ার), আকুর টাকুর পাড়া, টাঙ্গাইল',
                'message' => 'গেটে RentBD কিউআর পোস্টার লাগানোর পর আর বারবার ফোন ধরতে হয় না। ভাড়াটিয়ারা নিজেরাই কিউআর কোড স্ক্যান করে খালি ফ্ল্যাটের ছবি ও ভাড়া দেখে আবেদন করে। এটি বাড়িওয়ালাদের জন্য অসাধারণ প্রযুক্তি!',
                'rating' => 5,
                'status' => true,
            ],
            [
                'name' => 'তানভীর আহমেদ',
                'designation' => 'ব্যাংক কর্মকর্তা (ভাড়াটিয়া), টাঙ্গাইল সদর',
                'message' => 'নতুন শহরে এসে বাসা খোঁজা যে এত সহজ হতে পারে তা RentBD না থাকলে বুঝতাম না। ম্যাপে সরাসরি সবুজ পিন দেখে খালি ফ্ল্যাট বেছে নিতে পেরেছি এবং ডিজিটাল চুক্তিতে নিশ্চিন্তে উঠেছি।',
                'rating' => 5,
                'status' => true,
            ],
            [
                'name' => 'ইঞ্জি. মোশাররফ হোসেন',
                'designation' => 'বাণিজ্যিক প্লাজার মালিক, ভিক্টোরিয়া রোড',
                'message' => 'দোকান ও অফিস স্পেস ভাড়া দিতে গিয়ে ব্রোকারের ঝামেলা পোহাতে হয়নি। ভেরিফায়েড আবেদন পেয়ে মাত্র ৩ দিনে পুরো ফ্লোর ভাড়া দিয়েছি। RentBD রেন্টাল খাতকে পুরোপুরি বদলে দিয়েছে।',
                'rating' => 5,
                'status' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }

        // 9. RentBD Live Counters
        Counter::truncate();
        $counters = [
            ['title' => 'নিবন্ধিত ভবন', 'value' => 500, 'icon' => 'bi-building', 'status' => true],
            ['title' => 'সফল ভাড়া চুক্তি', 'value' => 1200, 'icon' => 'bi-file-earmark-check', 'status' => true],
            ['title' => 'যাচাইকৃত ব্যবহারকারী', 'value' => 3000, 'icon' => 'bi-person-check', 'status' => true],
            ['title' => 'রিয়েল-টাইম কিউআর স্ক্যান', 'value' => 15000, 'icon' => 'bi-qr-code-scan', 'status' => true],
        ];

        foreach ($counters as $c) {
            Counter::create($c);
        }

        // 10. RentBD FAQs
        Faq::truncate();
        $faqs = [
            [
                'question' => 'কিউআর কোড স্ক্যান করে কিভাবে খালি ফ্ল্যাট দেখা যায়?',
                'answer' => 'আপনার স্মার্টফোনের সাধারণ ক্যামেরা বা কিউআর স্ক্যানার দিয়ে ভবনের গেটে থাকা কিউআর কোডটি স্ক্যান করলেই তাৎক্ষণিক ওই ভবনের খালি ফ্ল্যাটের তালিকা, ছবি, সুবিধা ও ভাড়া দেখতে পাবেন।',
                'order' => 1,
                'status' => true,
            ],
            [
                'question' => 'Property ID (প্রপার্টি আইডি) কী এবং কেন এটি প্রয়োজন?',
                'answer' => 'প্রতিটি ভবন এবং প্রতিটি ফ্ল্যাট বা দোকানের জন্য একটি স্থায়ী ইউনিক ডিজিটাল কোড (যেমন: BD-TNG-000101)। এর মাধ্যমে কোনো সম্পত্তির নির্ভরযোগ্য তথ্য অনলাইনে খুঁজে পাওয়া ও চুক্তিপত্র করা যায়।',
                'order' => 2,
                'status' => true,
            ],
            [
                'question' => 'ভাড়াটিয়া হিসেবে কিভাবে আবেদন করব?',
                'answer' => 'পছন্দের ইউনিটের বিস্তারিত পেজে গিয়ে "আবেদন করুন" বাটনে ক্লিক করে সম্ভাব্য ওঠার তারিখ ও তথ্য দিয়ে ফরমটি সাবমিট করুন। বাড়িওয়ালা যাচাই করে সরাসরি আপনার সাথে যোগাযোগ করবেন।',
                'order' => 3,
                'status' => true,
            ],
            [
                'question' => 'বাড়িওয়ালারা কিভাবে কিউআর পোস্টার প্রিন্ট করবেন?',
                'answer' => 'বাড়িওয়ালা ড্যাশবোর্ড থেকে ভবনের পেজে গিয়ে "গেট পোস্টার প্রিন্ট" বাটনে ক্লিক করে যেকোনো সাধারণ প্রিন্টার থেকে এক ক্লিকে রঙিন বা সাদা-কালো পোস্টার প্রিন্ট করতে পারবেন।',
                'order' => 4,
                'status' => true,
            ],
            [
                'question' => 'ডিজিটাল ভাড়া চুক্তিপত্র কতটা আইনগতভাবে বৈধ?',
                'answer' => 'RentBD-এর ডিজিটাল চুক্তিপত্র বাংলাদেশের চুক্তি আইন ও প্রচলিত ভাড়া বিধিমালার ধারা অনুযায়ী তৈরি করা হয় এবং উভয় পক্ষের ডিজিটাল সম্মতি ও স্বাক্ষর সংরক্ষিত থাকে।',
                'order' => 5,
                'status' => true,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::create($f);
        }

        // 11. Run Tangail Sadar Buildings, Units & Rental Categories Seeders
        $this->call([
            PropertyTypeSeeder::class,
            PartnerSeeder::class,
            RentBDSadarSeeder::class,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
