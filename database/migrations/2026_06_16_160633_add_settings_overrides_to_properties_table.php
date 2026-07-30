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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_policy',
                'check_in_time',
                'check_out_time',
                'min_advance_booking_days',
                'max_advance_booking_days',
                'allow_instant_booking',
                'requires_id_verification',
            ]);
        });
    }
};
