<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dashboard_users')) {
            return;
        }

        Schema::create('dashboard_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'owner', 'manager', 'receptionist'])->default('receptionist');
            $table->json('permissions')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('monolith_vendor_id')->nullable();
            $table->unsignedBigInteger('monolith_employee_id')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('org_id');
            $table->index('role');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_users');
    }
};
