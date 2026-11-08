<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_id')->unique(); // e.g. BD-TNG-000101-F3B, BD-TNG-000101-S01
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->enum('category_type', ['residential', 'commercial', 'other'])->default('residential');
            // flat, house, sublet, mess, hostel, shop, office, showroom, warehouse, factory, garage, parking, roof, land_lease
            $table->string('category', 50)->default('flat');
            $table->string('title');
            $table->string('unit_number', 50); // e.g. 3B, Shop-01, G-1
            $table->string('floor_level', 50)->default('1st');
            $table->unsignedInteger('size_sqft')->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->unsignedTinyInteger('balconies')->nullable();
            $table->string('facing', 30)->nullable(); // South, North, East, West
            $table->decimal('monthly_rent', 12, 2);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('security_deposit', 12, 2)->default(0);
            $table->boolean('rent_negotiable')->default(false);
            $table->enum('gas_type', ['pipeline', 'cylinder', 'none'])->default('pipeline');
            $table->string('water_supply')->default('24/7 Deep Tube-well');
            $table->enum('electricity_type', ['prepaid', 'postpaid'])->default('prepaid');
            $table->enum('preferred_tenant', ['family', 'bachelor', 'female_bachelor', 'any', 'commercial'])->default('family');
            $table->date('available_from')->nullable();
            $table->boolean('is_vacant')->default(true); // Real-time vacancy toggle!
            $table->enum('status', ['vacant', 'occupied', 'booked', 'maintenance'])->default('vacant');
            $table->json('amenities')->nullable(); // Kitchen Cabinet, Geyser, AC Point, Tiled Floor, etc.
            $table->string('thumbnail')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('floor_plan_image')->nullable();
            $table->string('video_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['category_type', 'category']);
            $table->index('is_vacant');
            $table->index('status');
            $table->index('monthly_rent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_units');
    }
};

