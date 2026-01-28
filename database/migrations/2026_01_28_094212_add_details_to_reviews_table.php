<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_as_described')->default(true);
            $table->boolean('is_packaging_good')->default(true);
            $table->boolean('is_delivery_on_time')->default(true);
            $table->boolean('is_hidden')->default(false); // For admin moderation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_as_described', 'is_packaging_good', 'is_delivery_on_time', 'is_hidden']);
        });
    }
};
