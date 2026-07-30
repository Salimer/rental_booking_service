<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('orgs')) {
            return;
        }

        Schema::create('orgs', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('code', 100)->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->string('city', 100)->nullable();
            $table->text('address_ar')->nullable();
            $table->text('address_en')->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('preferred_currency', 10)->default('SAR');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending');
            $table->decimal('commission', 5, 2)->default(10.00);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status', 'rental_orgs_status_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orgs');
    }
};
