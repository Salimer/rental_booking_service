<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('orgs')->where('preferred_currency', 'YERN')->update(['preferred_currency' => 'YER_N']);
        DB::table('orgs')->where('preferred_currency', 'YERS')->update(['preferred_currency' => 'YER_S']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('orgs')->where('preferred_currency', 'YER_N')->update(['preferred_currency' => 'YERN']);
        DB::table('orgs')->where('preferred_currency', 'YER_S')->update(['preferred_currency' => 'YERS']);
    }
};
