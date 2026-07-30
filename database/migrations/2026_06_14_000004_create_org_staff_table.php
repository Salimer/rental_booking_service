<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('org_staff')) {
            return;
        }

        Schema::create('org_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('orgs')->onDelete('cascade');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('vendor_employee_id')->nullable();
            $table->enum('rental_role', ['owner', 'manager', 'receptionist'])->default('receptionist');
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('invited_by')->nullable();
            $table->timestamps();

            $table->unique(['org_id', 'vendor_id', 'vendor_employee_id'], 'org_staff_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_staff');
    }
};
