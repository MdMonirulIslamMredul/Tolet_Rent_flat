<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSocialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create([
            'site_name' => 'RentBD',
            'contact_email' => 'old@example.com',
            'contact_phone' => '+1 111-222-3333',
            'contact_address' => 'Old Address',
            'google_map_embed' => 'https://maps.google.com/old',
            'social_links' => [
                'facebook' => 'https://facebook.com/old',
                'linkedin' => 'https://linkedin.com/old',
                'youtube' => 'https://youtube.com/old',
            ],
        ]);
    }

    protected function getAdminUser(): User
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Web_Settings']);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@admin.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);
        $user->givePermissionTo('Web_Settings');

        return $user;
    }

    public function test_admin_can_view_contact_and_social_links_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/contact-social');

        $response->assertStatus(200);
        $response->assertSee('Contact &amp; Social Links', false);
        $response->assertSee('old@example.com');
        $response->assertSee('https://facebook.com/old');
    }

    public function test_admin_can_update_contact_and_social_links(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post('/admin/contact-social', [
            'contact_email' => 'contact@rentbd.com',
            'contact_phone' => '+880 1711-000101',
            'contact_address' => 'Victoria Road, Tangail Sadar, Bangladesh',
            'google_map_embed' => 'https://maps.google.com/new',
            'facebook_url' => 'https://facebook.com/rentbd',
            'twitter_url' => 'https://x.com/rentbd',
            'instagram_url' => 'https://instagram.com/rentbd',
            'linkedin_url' => 'https://linkedin.com/company/rentbd',
            'youtube_url' => 'https://youtube.com/@rentbd',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = Setting::first();
        $this->assertEquals('contact@rentbd.com', $setting->contact_email);
        $this->assertEquals('+880 1711-000101', $setting->contact_phone);
        $this->assertEquals('Victoria Road, Tangail Sadar, Bangladesh', $setting->contact_address);
        $this->assertEquals('https://maps.google.com/new', $setting->google_map_embed);
        $this->assertEquals('https://facebook.com/rentbd', $setting->social_links['facebook'] ?? null);
        $this->assertEquals('https://x.com/rentbd', $setting->social_links['twitter'] ?? null);
        $this->assertEquals('https://instagram.com/rentbd', $setting->social_links['instagram'] ?? null);
        $this->assertEquals('https://linkedin.com/company/rentbd', $setting->social_links['linkedin'] ?? null);
        $this->assertEquals('https://youtube.com/@rentbd', $setting->social_links['youtube'] ?? null);
    }

    public function test_frontend_footer_displays_all_social_links_and_google_map(): void
    {
        $setting = Setting::first();
        $setting->update([
            'social_links' => [
                'facebook' => 'https://facebook.com/rentbd',
                'twitter' => 'https://x.com/rentbd',
                'instagram' => 'https://instagram.com/rentbd',
                'linkedin' => 'https://linkedin.com/company/rentbd',
                'youtube' => 'https://youtube.com/@rentbd',
            ],
            'google_map_embed' => 'https://maps.google.com/embed/testmap',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('https://x.com/rentbd');
        $response->assertSee('https://instagram.com/rentbd');
        $response->assertSee('bi-twitter-x');
        $response->assertSee('bi-instagram');
        $response->assertSee('https://maps.google.com/embed/testmap');
    }
}
