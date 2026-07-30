<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('units', 'amenities')) {
            Schema::table('units', function (Blueprint $table) {
                $table->dropColumn('amenities');
            });
        }
    }

    public function down()
    {
        if (! Schema::hasColumn('units', 'amenities')) {
            Schema::table('units', function (Blueprint $table) {
                $table->json('amenities')->nullable()->after('images');
            });
        }
    }
};
