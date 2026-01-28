<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerPayoutRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = \App\Models\PayoutRequest::where('seller_id', Auth::id())
            ->with('processedBy')
            ->latest('requested_at');

        // Filter by status
        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }

        $requests = $query->paginate(15);

        // Calculate current pending balance
        $pendingPayouts = \App\Models\Order::where('seller_id', Auth::id())
            ->where('status', 'completed')
            ->where('payout_status', 'pending')
            ->sum('seller_earning');

        // Check for active request
        $hasActiveRequest = \App\Models\PayoutRequest::where('seller_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return view('seller.payout-requests', compact('requests', 'status', 'pendingPayouts', 'hasActiveRequest'));
    }
}
