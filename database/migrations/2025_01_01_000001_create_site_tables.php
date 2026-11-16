<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Land / new house / resale listings managed from admin
        Schema::create('properties', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique(); // SEO-friendly URL: /properties/3-bhk-villa-trivandrum
            $t->enum('type', ['land', 'new_house', 'resale'])->index();
            $t->string('location')->index();
            $t->unsignedBigInteger('price');
            $t->unsignedInteger('area_sqft')->nullable();
            $t->unsignedTinyInteger('bedrooms')->nullable();
            $t->text('description')->nullable();
            $t->string('image')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        // Key/value site content (hero text, WhatsApp number, banners...)
        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->text('value')->nullable();
        });

        // Leads from "Request a Free Estimate" and property enquiries
        Schema::create('enquiries', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone', 20);
            $t->text('message')->nullable();
            $t->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $t->boolean('is_read')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('properties');
    }
};
