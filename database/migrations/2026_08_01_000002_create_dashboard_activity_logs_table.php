<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dashboard_activity_logs')) {
            return;
        }

        Schema::create('dashboard_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dashboard_user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('action'); // e.g. org.created, booking.updated, etc.
            $table->string('subject_type')->nullable(); // e.g. Org, Unit, Booking
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('dashboard_user_id');
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_activity_logs');
    }
};
