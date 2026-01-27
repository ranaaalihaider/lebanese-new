<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\ProductType;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingSellers = User::where('role', 'seller')->where('status', 'pending')->count();
        $totalSellers = User::where('role', 'seller')->where('status', 'active')->count();
        $totalBuyers = User::where('role', 'buyer')->count();

        return view('admin.dashboard', compact('pendingSellers', 'totalSellers', 'totalBuyers'));
    }

    public function sellers(Request $request)
    {
        $query = User::where('role', 'seller')->with('sellerProfile')->withCount('products');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('sellerProfile', function ($q) use ($search) {
                        $q->where('store_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sellers = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get counts
        $pendingCount = User::where('role', 'seller')->where('status', 'pending')->count();
        $activeCount = User::where('role', 'seller')->where('status', 'active')->count();
        $rejectedCount = User::where('role', 'seller')->where('status', 'rejected')->count();

        return view('admin.sellers', compact('sellers', 'pendingCount', 'activeCount', 'rejectedCount'));
    }

    public function buyers(Request $request)
    {
        $query = User::where('role', 'buyer')
            ->withCount('buyerOrders')
            ->withSum('buyerOrders', 'total_price');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $buyers = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get stats
        $totalBuyers = User::where('role', 'buyer')->count();
        $activeBuyers = User::where('role', 'buyer')
            ->whereHas('buyerOrders', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })->count();

        return view('admin.buyers', compact('buyers', 'totalBuyers', 'activeBuyers'));
    }

    public function showBuyer($id)
    {
        $buyer = User::where('role', 'buyer')->findOrFail($id);

        $orders = Order::where('buyer_id', $id)
            ->with(['product', 'seller.sellerProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total_price'),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
        ];

        return view('admin.buyers.show', compact('buyer', 'orders', 'stats'));
    }

    public function approveSeller($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        return back()->with('success', 'Seller approved successfully.');
    }

    public function rejectSeller($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return back()->with('success', 'Seller rejected.');
    }

    public function deactivateSeller($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return back()->with('success', 'Seller deactivated successfully.');
    }

    public function activateSeller($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        return back()->with('success', 'Seller activated successfully.');
    }

    public function settings()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = \App\Models\Setting::getSetting('site_logo');
            if ($oldLogo && \Storage::exists('public/' . $oldLogo)) {
                \Storage::delete('public/' . $oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            \App\Models\Setting::setSetting('site_logo', $logoPath);
        }

        // Handle other settings
        foreach ($request->except(['_token', 'logo']) as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function editSeller($id)
    {
        $seller = User::findOrFail($id);
        $storeTypes = \App\Models\StoreType::all();
        return view('admin.sellers.edit', compact('seller', 'storeTypes'));
    }

    public function updateSeller(Request $request, $id)
    {
        $seller = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,' . $id,
            'store_name' => 'required|string|max:255',
            'store_type_id' => 'required',
            'pickup_location' => 'required|string|max:255',
            'language_preference' => 'required|in:EN,AR,HY,FR',
        ]);

        $seller->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        $storeTypeId = $request->store_type_id;
        if ($storeTypeId === 'other' && $request->filled('custom_store_type')) {
            $newType = \App\Models\StoreType::firstOrCreate(['name' => $request->custom_store_type]);
            $storeTypeId = $newType->id;
        }

        $seller->sellerProfile->update([
            'store_name' => $request->store_name,
            'store_tagline' => $request->store_tagline,
            'store_type_id' => $storeTypeId,
            'pickup_location' => $request->pickup_location,
            'language_preference' => $request->language_preference,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('admin.sellers')->with('success', 'Seller details updated successfully.');
    }

    public function products(Request $request)
    {
        $query = Product::with(['seller', 'type']);

        // Filter by product name
        if ($request->filled('product_name')) {
            $query->where('name', 'like', '%' . $request->product_name . '%');
        }

        // Filter by store
        if ($request->filled('store_id') && $request->store_id !== 'all') {
            $query->where('seller_id', $request->store_id);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Filter by Category
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('product_type_id', $request->category_id);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        // Global Stats (Calculated independently of pagination/search)
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();

        // Fetch sellers for dropdown
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->with('sellerProfile')
            ->orderBy('name')
            ->get();

        // Fetch Categories
        $productTypes = ProductType::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'sellers', 'totalProducts', 'activeProducts', 'productTypes'));
    }

    public function lists()
    {
        $productTypes = ProductType::withCount('products')->orderBy('name')->get();
        $storeTypes = \App\Models\StoreType::withCount('sellerProfiles')->orderBy('name')->get();

        return view('admin.lists', compact('productTypes', 'storeTypes'));
    }

    public function earnings(Request $request)
    {
        $query = \App\Models\Order::where('status', 'completed')
            ->with(['product', 'seller.sellerProfile']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('seller.sellerProfile', function ($q) use ($search) {
                        $q->where('store_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by store
        if ($request->filled('store_id') && $request->store_id !== 'all') {
            $query->where('seller_id', $request->store_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        // Calculate totals based on filters
        $totalQuery = \App\Models\Order::where('status', 'completed');

        // Apply same search to totals
        if ($request->filled('search')) {
            $search = $request->search;
            $totalQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('seller.sellerProfile', function ($q) use ($search) {
                        $q->where('store_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('store_id') && $request->store_id !== 'all') {
            $totalQuery->where('seller_id', $request->store_id);
        }
        if ($request->filled('date_from')) {
            $totalQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $totalQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $totalRevenue = $totalQuery->sum('platform_fee');
        $totalVolume = $totalQuery->sum('total_price');
        $totalSellerEarnings = $totalQuery->sum('seller_earning');
        $totalOrders = $totalQuery->count();

        // Get all sellers for dropdown
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->with('sellerProfile')
            ->orderBy('name')
            ->get();

        return view('admin.earnings', compact('orders', 'totalRevenue', 'totalVolume', 'totalSellerEarnings', 'totalOrders', 'sellers'));
    }

    public function orders(Request $request)
    {
        $query = \App\Models\Order::with(['buyer', 'seller.sellerProfile']);

        // Filter by Store
        if ($request->filled('store_id') && $request->store_id !== 'all') {
            $query->where('seller_id', $request->store_id);
        }

        // Filter by Buyer
        if ($request->filled('buyer_id') && $request->buyer_id !== 'all') {
            $query->where('buyer_id', $request->buyer_id);
        }

        // Filter by Order Number
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->paginate(20);

        // Fetch lists for filters
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->with('sellerProfile')
            ->orderBy('name')
            ->get();

        $buyers = User::where('role', 'buyer')
            ->orderBy('name')
            ->get();

        return view('admin.orders.index', compact('orders', 'sellers', 'buyers'));
    }

    public function showOrder(\App\Models\Order $order)
    {
        $order->load(['product', 'buyer', 'seller.sellerProfile']);
        return view('admin.orders.show', compact('order'));
    }

    public function showStore($sellerId)
    {
        $seller = User::with('sellerProfile')->findOrFail($sellerId);

        // Analytics
        $totalProducts = \App\Models\Product::where('seller_id', $sellerId)->count();
        $completedOrders = \App\Models\Order::where('seller_id', $sellerId)->where('status', 'completed')->count();
        $pendingOrders = \App\Models\Order::where('seller_id', $sellerId)->where('status', 'pending')->count();
        $totalSales = \App\Models\Order::where('seller_id', $sellerId)->where('status', 'completed')->sum('total_price');
        $totalRevenue = \App\Models\Order::where('seller_id', $sellerId)->where('status', 'completed')->sum('platform_fee');

        // Products
        $products = \App\Models\Product::where('seller_id', $sellerId)->with('type')->latest()->get();

        return view('admin.stores.show', compact('seller', 'totalProducts', 'completedOrders', 'pendingOrders', 'totalSales', 'totalRevenue', 'products'));
    }
    public function toggleProductStatus(\App\Models\Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Product status updated successfully.');
    }
}
