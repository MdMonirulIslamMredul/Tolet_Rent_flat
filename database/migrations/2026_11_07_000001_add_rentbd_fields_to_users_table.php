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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('role')->default('tenant')->after('phone_verified_at'); // owner, tenant, admin, agency
            $table->string('nid_number')->nullable()->after('role');
            $table->string('nid_front_path')->nullable()->after('nid_number');
            $table->string('nid_back_path')->nullable()->after('nid_front_path');
            $table->string('selfie_path')->nullable()->after('nid_back_path');
            $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified')->after('selfie_path');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->string('avatar')->nullable()->after('verified_at');
            $table->text('address')->nullable()->after('avatar');
            $table->string('emergency_contact')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'phone_verified_at',
                'role',
                'nid_number',
                'nid_front_path',
                'nid_back_path',
                'selfie_path',
                'verification_status',
                'verified_at',
                'avatar',
                'address',
                'emergency_contact',
            ]);
        });
    }
};

