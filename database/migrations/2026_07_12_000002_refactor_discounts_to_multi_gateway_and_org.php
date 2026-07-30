<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Drop old columns from rental_system_settings or drop the table itself
        Schema::dropIfExists('rental_system_settings');

        // 2. Create gateway_discounts table
        if (! Schema::hasTable('gateway_discounts')) {
            Schema::create('gateway_discounts', function (Blueprint $table) {
                $table->id();
                $table->string('gateway', 50)->unique();
                $table->boolean('enabled')->default(false);
                $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
                $table->decimal('discount_value', 10, 2)->default(0.00);
                $table->string('label_ar', 100)->nullable();
                $table->string('label_en', 100)->nullable();
                $table->timestamps();
            });
        }

        // 3. Add free night promotion columns to org_settings table
        if (Schema::hasTable('org_settings')) {
            Schema::table('org_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('org_settings', 'free_night_enabled')) {
                    $table->boolean('free_night_enabled')->default(false);
                }
                if (! Schema::hasColumn('org_settings', 'free_night_min_nights')) {
                    $table->unsignedTinyInteger('free_night_min_nights')->default(3);
                }
                if (! Schema::hasColumn('org_settings', 'free_night_max_nights')) {
                    $table->unsignedTinyInteger('free_night_max_nights')->nullable()->default(null);
                }
                if (! Schema::hasColumn('org_settings', 'free_nights_count')) {
                    $table->unsignedTinyInteger('free_nights_count')->default(1);
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('org_settings')) {
            Schema::table('org_settings', function (Blueprint $table) {
                $table->dropColumn(['free_night_enabled', 'free_night_min_nights', 'free_night_max_nights', 'free_nights_count']);
            });
        }

        Schema::dropIfExists('gateway_discounts');

        // Recreate rental_system_settings
        Schema::create('rental_system_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('gateway_discount_enabled')->default(false);
            $table->string('gateway_discount_gateway', 50)->nullable();
            $table->enum('gateway_discount_type', ['percent', 'fixed'])->default('percent');
            $table->decimal('gateway_discount_value', 10, 2)->default(0.00);
            $table->string('gateway_discount_label_ar', 100)->nullable();
            $table->string('gateway_discount_label_en', 100)->nullable();
            $table->boolean('free_night_enabled')->default(false);
            $table->unsignedTinyInteger('free_night_min_nights')->default(3);
            $table->unsignedTinyInteger('free_night_max_nights')->nullable();
            $table->unsignedTinyInteger('free_nights_count')->default(1);
            $table->timestamps();
        });
    }
};
