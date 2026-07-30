<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('properties')) {
            return;
        }

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('orgs')->onDelete('restrict');
            $table->foreignId('type_id')->constrained('types')->onDelete('restrict');
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('city', 100)->nullable();
            $table->text('address_ar')->nullable();
            $table->text('address_en')->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('logo')->nullable();
            $table->json('images')->nullable();
            $table->json('amenities')->nullable();
            $table->text('rules_ar')->nullable();
            $table->text('rules_en')->nullable();
            $table->unsignedInteger('max_guests')->default(1);
            $table->decimal('base_price', 10, 2)->default(0.00);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->string('slug')->nullable();
            $table->string('meta_title', 150)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->index(['org_id', 'status'], 'rental_properties_org_status_idx');
            $table->index(['type_id', 'status'], 'rental_properties_type_status_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
