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
        Schema::table('users', function (Blueprint $table) {
            $table->string('house_no')->nullable()->after('remember_token');
            $table->string('street')->nullable()->after('house_no');
            $table->string('city')->nullable()->after('street');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['house_no', 'street', 'city', 'state', 'postal_code']);
        });
    }
};
