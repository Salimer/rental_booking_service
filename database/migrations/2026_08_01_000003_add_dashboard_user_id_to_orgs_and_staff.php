<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orgs') && !Schema::hasColumn('orgs', 'dashboard_user_id')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->unsignedBigInteger('dashboard_user_id')->nullable()->after('vendor_id');
                $table->index('dashboard_user_id');
            });
        }

        if (Schema::hasTable('org_staff') && !Schema::hasColumn('org_staff', 'dashboard_user_id')) {
            Schema::table('org_staff', function (Blueprint $table) {
                $table->unsignedBigInteger('dashboard_user_id')->nullable()->after('vendor_employee_id');
                $table->index('dashboard_user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orgs') && Schema::hasColumn('orgs', 'dashboard_user_id')) {
            Schema::table('orgs', function (Blueprint $table) {
                $table->dropColumn('dashboard_user_id');
            });
        }

        if (Schema::hasTable('org_staff') && Schema::hasColumn('org_staff', 'dashboard_user_id')) {
            Schema::table('org_staff', function (Blueprint $table) {
                $table->dropColumn('dashboard_user_id');
            });
        }
    }
};
