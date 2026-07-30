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
        Schema::table('org_settings', function (Blueprint $table) {
            $table->string('payout_frequency', 20)->default('monthly')->after('free_nights_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('org_settings', function (Blueprint $table) {
            $table->dropColumn('payout_frequency');
        });
    }
};
