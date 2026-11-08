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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('building_id')->unique(); // e.g. BD-TNG-000101
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('division')->default('Dhaka');
            $table->string('district')->default('Tangail');
            $table->string('district_code', 10)->default('TNG');
            $table->string('thana_upazila')->default('Tangail Sadar');
            $table->string('area'); // e.g. Akur Takur Para, Biswas Betka
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedSmallInteger('total_floors')->default(1);
            $table->unsignedSmallInteger('total_units')->default(0);
            $table->unsignedSmallInteger('vacant_units_count')->default(0);
            $table->json('amenities')->nullable(); // Lift, Generator, Security Guard, CCTV, Gas, WASA, Parking, WiFi, etc.
            $table->string('thumbnail')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('video_url')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending_approval'])->default('active');
            $table->text('description')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->timestamps();

            $table->index(['district', 'thana_upazila']);
            $table->index('is_featured');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};

