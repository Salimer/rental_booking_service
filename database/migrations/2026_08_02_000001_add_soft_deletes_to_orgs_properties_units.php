<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orgs') && !Schema::hasColumn('orgs', 'deleted_at')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('properties') && !Schema::hasColumn('properties', 'deleted_at')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('units') && !Schema::hasColumn('units', 'deleted_at')) {
            Schema::table('units', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orgs') && Schema::hasColumn('orgs', 'deleted_at')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'deleted_at')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('units') && Schema::hasColumn('units', 'deleted_at')) {
            Schema::table('units', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
