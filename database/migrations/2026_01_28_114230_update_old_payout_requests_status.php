<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update old payout requests where all orders have been paid
        // Get all pending payout requests
        $pendingRequests = DB::table('payout_requests')
            ->where('status', 'pending')
            ->get();

        foreach ($pendingRequests as $request) {
            // Check if seller has any pending orders
            $hasPendingOrders = DB::table('orders')
                ->where('seller_id', $request->seller_id)
                ->where('status', 'completed')
                ->where('payout_status', 'pending')
                ->exists();

            // If no pending orders, mark request as approved
            if (!$hasPendingOrders) {
                DB::table('payout_requests')
                    ->where('id', $request->id)
                    ->update([
                        'status' => 'approved',
                        'processed_at' => now(),
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as we don't know which were originally pending
    }
};
