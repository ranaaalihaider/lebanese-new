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
        // Convert existing data to JSON format for default locale 'en'
        \DB::statement("UPDATE products SET name = JSON_OBJECT('en', name) WHERE name IS NOT NULL AND NOT JSON_VALID(name)");
        \DB::statement("UPDATE products SET description = JSON_OBJECT('en', description) WHERE description IS NOT NULL AND NOT JSON_VALID(description)");

        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->change(); // Assuming it was string
            $table->text('description')->change(); // Assuming it was text
        });
    }
};
