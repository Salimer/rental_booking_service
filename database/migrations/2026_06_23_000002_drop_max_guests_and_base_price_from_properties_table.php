<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'max_guests')) {
                $table->dropColumn('max_guests');
            }
            if (Schema::hasColumn('properties', 'base_price')) {
                $table->dropColumn('base_price');
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedInteger('max_guests')->default(1);
            $table->decimal('base_price', 10, 2)->default(0.00);
        });
    }
};
