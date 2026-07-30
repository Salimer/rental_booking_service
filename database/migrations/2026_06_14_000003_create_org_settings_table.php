<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('org_settings')) {
            return;
        }

        Schema::create('org_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->unique()->constrained('orgs')->onDelete('cascade');
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict', 'non_refundable'])->default('moderate');
            $table->time('check_in_time')->default('14:00:00');
            $table->time('check_out_time')->default('11:00:00');
            $table->unsignedInteger('min_advance_booking_days')->default(1);
            $table->unsignedInteger('max_advance_booking_days')->default(365);
            $table->boolean('allow_instant_booking')->default(true);
            $table->boolean('requires_id_verification')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_settings');
    }
};
