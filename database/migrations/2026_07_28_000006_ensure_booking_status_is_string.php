<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `bookings` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE `bookings` MODIFY COLUMN `payment_status` VARCHAR(50) NOT NULL DEFAULT 'unpaid'");
        }
    }

    public function down(): void
    {
        // Keep down migration non-destructive
    }
};
