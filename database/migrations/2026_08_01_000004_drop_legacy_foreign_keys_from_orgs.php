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
            Schema::disableForeignKeyConstraints();
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
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
    }
};
