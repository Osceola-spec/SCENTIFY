<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Metrik Utama
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalVariants = ProductVariant::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $completedOrders = Order::where('status', 'Completed')->count();
        
        // 2. Kalkulasi Pendapatan
        $totalRevenue = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])->sum('total_amount');
        $monthlyRevenue = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $todayRevenue = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])
            ->whereDate('created_at', now())
            ->sum('total_amount');

        // 3. Data Tabel Widget
        $recentProducts = Product::with('brand')->latest()->take(5)->get();
        $upcomingOrders = Order::with('user')->whereIn('status', ['Pending', 'Paid', 'Shipped'])->latest()->take(5)->get();
        $lowStockVariants = ProductVariant::with('product')->where('stock', '<=', 10)->orderBy('stock')->take(5)->get();

        // 4. Kueri Grafik Pendapatan Bulanan (Jan-Des Tahun Ini)
        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[] = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');
        }

        // 5. Kueri Grafik Segmentasi Penjualan Gender
        $menSales = OrderItem::whereHas('variant.product', function($q) {
            $q->where('gender_type', 'Men');
        })->whereHas('order', function($q) {
            $q->whereIn('status', ['Paid', 'Shipped', 'Completed']);
        })->sum('quantity');

        $womenSales = OrderItem::whereHas('variant.product', function($q) {
            $q->where('gender_type', 'Women');
        })->whereHas('order', function($q) {
            $q->whereIn('status', ['Paid', 'Shipped', 'Completed']);
        })->sum('quantity');

        $unisexSales = OrderItem::whereHas('variant.product', function($q) {
            $q->where('gender_type', 'Unisex');
        })->whereHas('order', function($q) {
            $q->whereIn('status', ['Paid', 'Shipped', 'Completed']);
        })->sum('quantity');

        $totalSales = $menSales + $womenSales + $unisexSales;
        
        $genderData = [
            $totalSales > 0 ? round(($menSales / $totalSales) * 100) : 0,
            $totalSales > 0 ? round(($womenSales / $totalSales) * 100) : 0,
            $totalSales > 0 ? round(($unisexSales / $totalSales) * 100) : 0,
        ];

        // 6. Kembalikan semua data ke View
        return view('admin.dashboard', compact(
            'totalProducts', 'totalBrands', 'totalVariants', 'totalOrders',
            'pendingOrders', 'completedOrders', 'totalRevenue', 'monthlyRevenue',
            'todayRevenue', 'recentProducts', 'upcomingOrders', 'lowStockVariants',
            'revenueData', 'genderData'
        ));
    }
}