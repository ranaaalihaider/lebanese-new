<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;
use App\Models\StoreType;

class InitialCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default product types if they don't exist
        $productTypes = [
            'Electronics',
            'Clothing',
            'Food & Beverages',
            'Home & Garden',
            'Sports & Outdoors',
            'Books & Media',
            'Health & Beauty',
            'Toys & Games',
        ];

        foreach ($productTypes as $type) {
            ProductType::firstOrCreate(['name' => $type]);
        }

        // Create default store types if they don't exist
        $storeTypes = [
            'General Store',
            'Specialty Shop',
            'Boutique',
            'Grocery Store',
            'Electronics Store',
            'Fashion Store',
            'Restaurant',
            'Cafe',
        ];

        foreach ($storeTypes as $type) {
            StoreType::firstOrCreate(['name' => $type]);
        }

        $this->command->info('Initial categories created successfully!');
    }
}
