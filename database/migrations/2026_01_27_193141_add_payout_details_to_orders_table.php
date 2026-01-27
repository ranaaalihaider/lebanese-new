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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payout_status')->default('pending'); // pending, paid
            $table->decimal('payout_amount', 10, 2)->nullable();
            $table->timestamp('payout_date')->nullable();
            $table->string('payout_method')->nullable();
            $table->string('payout_transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payout_status', 'payout_amount', 'payout_date', 'payout_method', 'payout_transaction_id']);
        });
    }
};
