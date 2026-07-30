<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('booking_payments')) {
            return;
        }

        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('reference_no', 100)->unique();
            $table->string('gateway', 100);
            $table->string('gateway_transaction_ref')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->enum('status', ['initiated', 'pending', 'paid', 'failed', 'refunded'])->default('initiated');
            $table->string('payment_method', 100)->nullable();
            $table->json('response_payload')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_payments');
    }
};
