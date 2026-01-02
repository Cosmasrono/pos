<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use App\Models\Shift;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get today's sales
        $todaySales = Sale::whereDate('created_at', today())
            ->sum('total_amount');

        // Get total products
        $totalProducts = Product::where('is_active', true)->count();

        // Get low stock products
        $lowStockProducts = Product::whereRaw('quantity_in_stock <= reorder_level')
            ->where('is_active', true)
            ->count();

        // Get active shift
        $activeShift = Shift::where('status', 'open')
            ->where('cashier_id', auth()->id())
            ->first();

        // Get recent sales
        $recentSales = Sale::latest()
            ->take(10)
            ->with(['cashier', 'customer'])
            ->get();

        return view('dashboard.index', [
            'todaySales' => $todaySales,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'activeShift' => $activeShift,
            'recentSales' => $recentSales,
        ]);
    }
}
