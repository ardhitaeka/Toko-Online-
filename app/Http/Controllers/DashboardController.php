<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $topCategoryQuery = Category::withCount('products')
            ->where('is_active', true)
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        $maxProductsCount = $topCategoryQuery->max('products_count') ?: 1;

        $topCategories = $topCategoryQuery->map(fn ($cat) => [
            'name' => $cat->name,
            'total' => $cat->products_count,
            'percentage' => round($cat->products_count / $maxProductsCount * 100),
            'image' => $cat->image,
        ]);

        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(4)
            ->get();

        return view('dashboard', compact('stats', 'recentOrders', 'topCategories', 'lowStockProducts'));
    }
}
