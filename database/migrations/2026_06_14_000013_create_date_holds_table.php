<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('date_holds')) {
            return;
        }

        Schema::create('date_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('cascade');
            $table->string('hold_token', 100)->nullable()->unique();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('nights_count')->default(1);
            $table->integer('guests_count')->default(1);
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_email')->nullable();
            $table->text('guest_note')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('SAR');
            $table->string('status', 30)->default('active');
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('date_holds');
    }
};
