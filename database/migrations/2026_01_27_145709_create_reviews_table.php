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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // We reference 'orders' table. Since orders can have multiple items (product_id), this links usage to a specific checkout instance.
            // Note: orders table doesn't have 'id' as primary key in some systems, but Laravel usually assumes 'id'.
            // Based on previous debug, orders table DOES have 'id' (from migration 2026_01_26_213911_create_orders_table.php line 14: $table->id();).
            // It was wishlists that didn't have ID. Orders table has ID.
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Prevent multiple reviews for the same product in the same order by the same user
            $table->unique(['user_id', 'order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
