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
        Schema::table('org_staff', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('rental_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('org_staff', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
