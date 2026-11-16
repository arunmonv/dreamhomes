<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // "Featured Properties" row on the home page is chosen in admin
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $t) {
            $t->boolean('is_featured')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('properties', fn (Blueprint $t) => $t->dropColumn('is_featured'));
    }
};
