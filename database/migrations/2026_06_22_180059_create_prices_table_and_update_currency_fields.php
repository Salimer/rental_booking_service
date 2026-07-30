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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('priceable_id');
            $table->string('priceable_type');
            $table->string('price_type', 30)->default('default');
            $table->string('name', 150)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('days_of_week')->nullable();
            $table->decimal('price_yer_n', 15, 2);
            $table->decimal('price_yer_s', 15, 2);
            $table->decimal('price_sar', 15, 2);
            $table->decimal('price_usd', 15, 2);
            $table->timestamps();

            $table->index(['priceable_type', 'priceable_id', 'price_type'], 'rental_prices_priceable_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('preferred_currency', 10)->nullable();
        });

        // Widen currency columns to varchar(10) to support YER_N, YER_S
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('currency', 10)->change();
        });
        Schema::table('booking_transactions', function (Blueprint $table) {
            $table->string('currency', 10)->change();
        });
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->string('currency', 10)->change();
        });

        // Drop unnecessary single-currency columns
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['price', 'weekend_price']);
        });
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('override_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->decimal('override_price', 10, 2)->nullable();
        });

        Schema::table('units', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('weekend_price', 10, 2)->nullable();
        });

        Schema::table('booking_payments', function (Blueprint $table) {
            $table->string('currency', 3)->change();
        });
        Schema::table('booking_transactions', function (Blueprint $table) {
            $table->string('currency', 3)->change();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('currency', 3)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_currency');
        });

        Schema::dropIfExists('prices');
    }
};
