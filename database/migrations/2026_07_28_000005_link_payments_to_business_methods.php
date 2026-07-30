<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_payments', 'business_payment_method_id')) {
                $table->unsignedBigInteger('business_payment_method_id')->nullable();
            }
            if (! Schema::hasColumn('booking_payments', 'payment_method_key')) {
                $table->string('payment_method_key', 120)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            if (Schema::hasColumn('booking_payments', 'payment_method_key')) {
                $table->dropColumn('payment_method_key');
            }
            if (Schema::hasColumn('booking_payments', 'business_payment_method_id')) {
                $table->dropColumn('business_payment_method_id');
            }
        });
    }
};
