<?php

namespace Tests\Feature;

use App\Models\Rental\Building;
use App\Models\Rental\DigitalAgreement;
use App\Models\Rental\RentalApplication;
use App\Models\Rental\RentalUnit;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RentBDSadarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentBDRentalApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $tenant;
    protected Building $building;
    protected RentalUnit $unit;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'site_name' => 'RentBD',
            'contact_phone' => '+880 1711-000101',
        ]);

        $this->seed(RentBDSadarSeeder::class);

        $this->owner = User::where('email', 'owner@rentbd.com')->firstOrFail();
        $this->tenant = User::where('email', 'tenant@rentbd.com')->firstOrFail();
        $this->building = Building::where('building_id', 'BD-TNG-000101')->firstOrFail();
        $this->unit = RentalUnit::where('unit_id', 'BD-TNG-000101-F3B')->firstOrFail();
    }

    public function test_tenant_can_view_rental_application_form(): void
    {
        $response = $this->actingAs($this->tenant)->get(route('rentals.apply.create', $this->unit->unit_id));

        $response->assertStatus(200);
        $response->assertSee('ভাড়াটিয়া আবেদন ফরম');
        $response->assertSee($this->unit->unit_id);
    }

    public function test_tenant_can_submit_rental_application(): void
    {
        $response = $this->actingAs($this->tenant)->post(route('rentals.apply.store', $this->unit->unit_id), [
            'proposed_move_in_date' => now()->addDays(7)->format('Y-m-d'),
            'family_members_count' => 4,
            'profession' => 'Bank Officer',
            'current_address' => 'Akur Takur Para, Tangail',
            'message_to_owner' => 'Looking for clean family rental unit.',
        ]);

        $response->assertRedirect(route('tenant.dashboard'));
        $this->assertDatabaseHas('rental_applications', [
            'unit_id' => $this->unit->id,
            'building_id' => $this->building->id,
            'tenant_id' => $this->tenant->id,
            'owner_id' => $this->owner->id,
            'profession' => 'Bank Officer',
            'status' => 'pending',
        ]);
    }

    public function test_owner_can_view_and_shortlist_application(): void
    {
        $application = RentalApplication::create([
            'application_no' => 'APP-TNG-TEST01',
            'unit_id' => $this->unit->id,
            'building_id' => $this->building->id,
            'tenant_id' => $this->tenant->id,
            'owner_id' => $this->owner->id,
            'proposed_move_in_date' => now()->addDays(5),
            'family_members_count' => 3,
            'profession' => 'Engineer',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)->get(route('owner.applications.show', $application));
        $response->assertStatus(200);
        $response->assertSee('APP-TNG-TEST01');

        $updateResponse = $this->actingAs($this->owner)->post(route('owner.applications.update-status', $application), [
            'status' => 'shortlisted',
            'owner_notes' => 'Tenant profile looks great.',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('rental_applications', [
            'id' => $application->id,
            'status' => 'shortlisted',
        ]);
    }

    public function test_owner_can_generate_digital_agreement(): void
    {
        $response = $this->actingAs($this->owner)->post(route('owner.agreements.store'), [
            'unit_id' => $this->unit->id,
            'tenant_email_or_phone' => $this->tenant->email,
            'monthly_rent' => 18000,
            'security_deposit' => 36000,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addYear()->format('Y-m-d'),
            'terms_conditions' => 'Standard residential tenancy terms according to Bangladesh law.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('digital_agreements', [
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'owner_id' => $this->owner->id,
            'monthly_rent' => 18000,
        ]);
    }
}
