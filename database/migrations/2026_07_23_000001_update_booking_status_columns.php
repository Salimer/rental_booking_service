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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status', 50)->default('unpaid')->change();
            $table->string('status', 50)->default('pending')->change();
        });

        Schema::table('booking_payments', function (Blueprint $table) {
            $table->string('status', 50)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded', 'failed'])->default('unpaid')->change();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'rejected', 'no_show'])->default('pending')->change();
        });

        Schema::table('booking_payments', function (Blueprint $table) {
            $table->enum('status', ['initiated', 'pending', 'paid', 'failed', 'refunded'])->default('initiated')->change();
        });
    }
};
