<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create rental_system_settings table
        if (! Schema::hasTable('rental_system_settings')) {
            Schema::create('rental_system_settings', function (Blueprint $table) {
                $table->id();

                // Gateway Discount settings
                $table->boolean('gateway_discount_enabled')->default(false);
                $table->string('gateway_discount_gateway', 50)->nullable();
                $table->enum('gateway_discount_type', ['percent', 'fixed'])->default('percent');
                $table->decimal('gateway_discount_value', 10, 2)->default(0.00);
                $table->string('gateway_discount_label_ar', 100)->nullable();
                $table->string('gateway_discount_label_en', 100)->nullable();

                // Free Night Promotion settings
                $table->boolean('free_night_enabled')->default(false);
                $table->unsignedTinyInteger('free_night_min_nights')->default(3);
                $table->unsignedTinyInteger('free_night_max_nights')->nullable();
                $table->unsignedTinyInteger('free_nights_count')->default(1);

                $table->timestamps();
            });
        }

        // 2. Add limit_per_user to coupons table
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (! Schema::hasColumn('coupons', 'limit_per_user')) {
                    $table->unsignedTinyInteger('limit_per_user')->nullable()->default(null)->after('max_uses');
                }
            });
        }

        // 3. Create coupon_usages table
        if (! Schema::hasTable('coupon_usages')) {
            Schema::create('coupon_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
                $table->timestamp('used_at')->nullable();
                $table->timestamps();

                $table->index(['coupon_id', 'user_id'], 'coupon_user_usage_idx');
            });
        }

        // 4. Add columns to booking_transactions table
        if (Schema::hasTable('booking_transactions')) {
            Schema::table('booking_transactions', function (Blueprint $table) {
                if (! Schema::hasColumn('booking_transactions', 'free_night_discount_amount')) {
                    $table->decimal('free_night_discount_amount', 10, 2)->default(0.00)->after('discount_amount');
                }
                if (! Schema::hasColumn('booking_transactions', 'gateway_discount_amount')) {
                    $table->decimal('gateway_discount_amount', 10, 2)->default(0.00)->after('free_night_discount_amount');
                }
                if (! Schema::hasColumn('booking_transactions', 'discount_breakdown')) {
                    $table->json('discount_breakdown')->nullable()->after('gateway_discount_amount');
                }
            });
        }
    }

    public function down()
    {
        // 1. Rollback booking_transactions columns
        if (Schema::hasTable('booking_transactions')) {
            Schema::table('booking_transactions', function (Blueprint $table) {
                if (Schema::hasColumn('booking_transactions', 'discount_breakdown')) {
                    $table->dropColumn('discount_breakdown');
                }
                if (Schema::hasColumn('booking_transactions', 'gateway_discount_amount')) {
                    $table->dropColumn('gateway_discount_amount');
                }
                if (Schema::hasColumn('booking_transactions', 'free_night_discount_amount')) {
                    $table->dropColumn('free_night_discount_amount');
                }
            });
        }

        // 2. Drop coupon_usages table
        Schema::dropIfExists('coupon_usages');

        // 3. Rollback limit_per_user on coupons
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (Schema::hasColumn('coupons', 'limit_per_user')) {
                    $table->dropColumn('limit_per_user');
                }
            });
        }

        // 4. Drop rental_system_settings table
        Schema::dropIfExists('rental_system_settings');
    }
};
