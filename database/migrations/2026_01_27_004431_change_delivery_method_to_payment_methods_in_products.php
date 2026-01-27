<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Rename and change column type to JSON
            $table->json('payment_methods')->nullable()->after('price');
        });

        // Migrate existing data
        DB::table('products')->get()->each(function ($product) {
            if ($product->delivery_method) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['payment_methods' => json_encode([$product->delivery_method])]);
            }
        });

        // Drop old column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('delivery_method');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('delivery_method')->nullable()->after('price');
        });

        // Migrate data back
        DB::table('products')->get()->each(function ($product) {
            if ($product->payment_methods) {
                $methods = json_decode($product->payment_methods, true);
                if (!empty($methods)) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['delivery_method' => $methods[0]]);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('payment_methods');
        });
    }
};
