<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orgs')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_id')->nullable()->change();
                $table->unsignedBigInteger('module_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orgs')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_id')->nullable(false)->change();
                $table->unsignedBigInteger('module_id')->nullable(false)->change();
            });
        }
    }
};
