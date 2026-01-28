<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Convert plain text names to JSON format for Spatie Translatable
        $types = DB::table('product_types')->get();

        foreach ($types as $type) {
            $name = $type->name;

            // Check if it's already JSON
            $decoded = json_decode($name, true);

            if (!$decoded) {
                // It's plain text, convert to JSON
                $jsonName = json_encode(['en' => $name]);
                DB::table('product_types')
                    ->where('id', $type->id)
                    ->update(['name' => $jsonName]);
            }
        }
    }

    public function down()
    {
        // Optionally revert back to plain text
    }
};
