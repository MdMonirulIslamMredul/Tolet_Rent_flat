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
        Schema::create('ratings_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('building_id')->nullable()->constrained('buildings')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('rental_units')->cascadeOnDelete();
            $table->enum('review_type', ['tenant_to_owner', 'owner_to_tenant'])->default('tenant_to_owner');
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5
            $table->text('comment')->nullable();
            $table->boolean('is_verified_stay')->default(true);
            $table->enum('status', ['published', 'hidden'])->default('published');
            $table->timestamps();

            $table->index(['reviewee_id', 'status']);
            $table->index(['building_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings_reviews');
    }
};

