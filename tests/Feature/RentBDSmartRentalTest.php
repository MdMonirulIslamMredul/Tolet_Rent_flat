<?php

namespace Tests\Feature;

use App\Models\Rental\Building;
use App\Models\Rental\RentalUnit;
use App\Models\User;
use App\Services\PropertyIdService;
use App\Services\SmartSearchService;
use Database\Seeders\RentBDSadarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RentBDSmartRentalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => RentBDSadarSeeder::class]);
    }

    /**
     * Test Property ID Generation Service for Building & Unit
     */
    public function test_property_id_generation_format(): void
    {
        $buildingId = PropertyIdService::generateBuildingId('Tangail');
        $this->assertStringStartsWith('BD-TNG-', $buildingId);
        $this->assertEquals(13, strlen($buildingId)); // e.g. BD-TNG-000105

        $building = Building::where('building_id', 'BD-TNG-000101')->firstOrFail();

        $unitId = PropertyIdService::generateUnitId($building, 'flat', '3B', 3);
        $this->assertStringStartsWith('BD-TNG-000101-F3B', $unitId);

        $shopUnitId = PropertyIdService::generateUnitId($building, 'shop', '05', 1);
        $this->assertStringStartsWith('BD-TNG-000101-S05', $shopUnitId);

        $garageUnitId = PropertyIdService::generateUnitId($building, 'garage', '02', 0);
        $this->assertStringStartsWith('BD-TNG-000101-G02', $garageUnitId);
    }

    /**
     * Test Natural Language & AI Smart Search parsing in Bengali & English
     */
    public function test_smart_search_nlp_parsing(): void
    {
        // 1. Bengali query with budget, bedrooms, category, and location
        $query1 = "টাঙ্গাইল সদরে ১৫ হাজার টাকার মধ্যে ৩ বেডরুম ফ্ল্যাট চাই";
        $parsed1 = SmartSearchService::parse($query1);

        $this->assertEquals('Tangail', $parsed1['district']);
        $this->assertEquals('Tangail Sadar', $parsed1['thana_upazila']);
        $this->assertEquals('flat', $parsed1['category']);
        $this->assertEquals(3, $parsed1['bedrooms']);
        $this->assertEquals(15000, $parsed1['max_rent']);

        // 2. Commercial shop query
        $query2 = "টাঙ্গাইলে দোকান চাই";
        $parsed2 = SmartSearchService::parse($query2);

        $this->assertEquals('Tangail', $parsed2['district']);
        $this->assertEquals('shop', $parsed2['category']);
        $this->assertEquals('commercial', $parsed2['category_type']);

        // 3. Garage query
        $query3 = "গ্যারেজ বা পার্কিং";
        $parsed3 = SmartSearchService::parse($query3);
        $this->assertEquals('garage', $parsed3['category']);
    }

    /**
     * Test Public Building QR Landing Page (/b/{building_id})
     */
    public function test_building_qr_landing_page_renders_realtime_vacancy(): void
    {
        $response = $this->get('/b/BD-TNG-000101');
        $response->assertStatus(200);
        $response->assertSee('BD-TNG-000101');
        $response->assertSee('রিয়েল-টাইম');
        $response->assertSee('স্বপ্ননীড় টাওয়ার');
    }

    /**
     * Test Printable Gate Poster Route
     */
    public function test_gate_poster_printable_page_renders(): void
    {
        $response = $this->get('/b/BD-TNG-000101/poster');
        $response->assertStatus(200);
        $response->assertSee('টু-লেট • TO-LET');
        $response->assertSee('BD-TNG-000101');
    }

    /**
     * Test Map Markers API returns green/red/blue pins
     */
    public function test_map_markers_api(): void
    {
        $response = $this->getJson('/api/map-buildings?district=Tangail');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'count',
            'buildings' => [
                '*' => ['id', 'building_id', 'name', 'lat', 'lng', 'marker_color', 'vacant_units']
            ]
        ]);
        $response->assertJsonPath('success', true);
    }

    /**
     * Test 1-Click Live Vacancy Toggle on Unit
     */
    public function test_owner_vacancy_toggle(): void
    {
        $owner = User::where('email', 'owner@rentbd.com')->firstOrFail();
        $unit = RentalUnit::whereHas('building', fn ($q) => $q->where('owner_id', $owner->id))->firstOrFail();

        $initialVacancy = $unit->is_vacant;

        // Toggle vacancy via authenticated post
        $response = $this->actingAs($owner)->postJson("/owner/units/{$unit->id}/toggle-vacancy");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_vacant' => !$initialVacancy,
        ]);

        $unit->refresh();
        $this->assertEquals(!$initialVacancy, $unit->is_vacant);
    }
}
