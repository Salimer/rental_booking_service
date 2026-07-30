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
        // 1. Drop settings columns on properties if they exist
        Schema::table('properties', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach ([
                'cancellation_policy',
                'check_in_time',
                'check_out_time',
                'min_advance_booking_days',
                'max_advance_booking_days',
                'allow_instant_booking',
                'requires_id_verification',
            ] as $column) {
                if (Schema::hasColumn('properties', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // 2. Create the property_settings table
        Schema::create('property_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->unique()->constrained('properties')->onDelete('cascade');
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict', 'non_refundable'])->default('moderate');
            $table->time('check_in_time')->default('14:00:00');
            $table->time('check_out_time')->default('11:00:00');
            $table->unsignedInteger('min_advance_booking_days')->default(1);
            $table->unsignedInteger('max_advance_booking_days')->default(365);
            $table->boolean('allow_instant_booking')->default(true);
            $table->boolean('auto_approve_bookings')->default(true);
            $table->boolean('requires_id_verification')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_settings');

        // Add settings columns back to properties
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict', 'non_refundable'])->nullable()->after('rules_en');
            $table->time('check_in_time')->nullable()->after('cancellation_policy');
            $table->time('check_out_time')->nullable()->after('check_in_time');
            $table->unsignedInteger('min_advance_booking_days')->nullable()->after('check_out_time');
            $table->unsignedInteger('max_advance_booking_days')->nullable()->after('min_advance_booking_days');
            $table->boolean('allow_instant_booking')->nullable()->after('max_advance_booking_days');
            $table->boolean('requires_id_verification')->nullable()->after('allow_instant_booking');
        });
    }
};
