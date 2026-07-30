<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('property_id')->constrained('properties')->onDelete('restrict');
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->foreignId('org_id')->constrained('orgs')->onDelete('restrict');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('nights_count');
            $table->unsignedInteger('guests_count')->default(1);
            $table->string('guest_name');
            $table->string('guest_phone', 30);
            $table->string('guest_email', 150)->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded', 'failed'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'rejected', 'no_show'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->enum('cancelled_by', ['customer', 'org_staff', 'system', 'admin'])->nullable();
            $table->unsignedBigInteger('cancelled_by_user_id')->nullable();
            $table->text('guest_note')->nullable();
            $table->text('internal_note')->nullable();
            $table->dateTime('submitted_at');
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('checked_in_at')->nullable();
            $table->dateTime('checked_out_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status'], 'rental_bookings_user_status_idx');
            $table->index(['org_id', 'status', 'check_in_date'], 'rental_bookings_org_status_date_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
