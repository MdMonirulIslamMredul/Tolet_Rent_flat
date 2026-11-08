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
        Schema::create('digital_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_no')->unique(); // e.g. AGR-BD-TNG-2026-001
            $table->foreignId('unit_id')->constrained('rental_units')->cascadeOnDelete();
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('monthly_rent', 12, 2);
            $table->decimal('security_deposit', 12, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('utility_terms')->nullable();
            $table->string('owner_signature')->nullable();
            $table->timestamp('owner_signed_at')->nullable();
            $table->string('tenant_signature')->nullable();
            $table->timestamp('tenant_signed_at')->nullable();
            $table->enum('status', ['draft', 'sent_to_tenant', 'signed', 'active', 'terminated'])->default('draft');
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['owner_id', 'status']);
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_agreements');
    }
};

