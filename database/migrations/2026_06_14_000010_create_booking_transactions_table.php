<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('booking_transactions')) {
            return;
        }

        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('admin_commission', 10, 2)->default(0.00);
            $table->decimal('org_amount', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('SAR');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_transactions');
    }
};
