<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orgs')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            try {
                Schema::table('orgs', function (Blueprint $table) {
                    $table->dropForeign('orgs_module_id_foreign');
                });
            } catch (\Exception $e) {}

            try {
                Schema::table('orgs', function (Blueprint $table) {
                    $table->dropForeign('orgs_vendor_id_foreign');
                });
            } catch (\Exception $e) {}

            try {
                Schema::table('orgs', function (Blueprint $table) {
                    $table->dropForeign('orgs_zone_id_foreign');
                });
            } catch (\Exception $e) {}
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function down(): void
    {
    }
};
