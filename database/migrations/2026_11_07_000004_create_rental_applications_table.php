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
        Schema::create('rental_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no')->unique(); // e.g. APP-2026-0001
            $table->foreignId('unit_id')->constrained('rental_units')->cascadeOnDelete();
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'viewed', 'shortlisted', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->date('proposed_move_in_date')->nullable();
            $table->unsignedTinyInteger('family_members_count')->nullable();
            $table->string('profession')->nullable();
            $table->string('monthly_income_range')->nullable();
            $table->text('current_address')->nullable();
            $table->text('message_to_owner')->nullable();
            $table->text('owner_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['owner_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_applications');
    }
};

