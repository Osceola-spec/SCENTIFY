<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Metrik Utama (Angka Total Saat Ini)
        $totalProducts   = Product::count();
        $totalBrands     = Brand::count();
        $totalVariants   = ProductVariant::count();
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'Pending')->count();
        $completedOrders = Order::where('status', 'Completed')->count();
        
        // Status valid untuk perhitungan finansial
        $validStatuses  = ['Paid', 'Shipped', 'Completed'];
        $totalRevenue   = Order::whereIn('status', $validStatuses)->sum('total_amount');

        // ==========================================
        // PERHITUNGAN PERSENTASE LIVE (METRIK UTAMA)
        // ==========================================

        // A. Persentase Total Pesanan (Bulan Ini vs Bulan Lalu)
        $ordersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $ordersLastMonth = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        
        $orderDiffPercentage = 0;
        if ($ordersLastMonth > 0) {
            $orderDiffPercentage = round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100);
        } elseif ($ordersThisMonth > 0) {
            $orderDiffPercentage = 100; // Jika bulan lalu 0 tapi bulan ini ada penjualan, langsung +100%
        }

        // B. Persentase Pesanan Selesai (Bulan Ini vs Bulan Lalu)
        $completedThisMonth = Order::where('status', 'Completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $completedLastMonth = Order::where('status', 'Completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $completedDiffPercentage = 0;
        if ($completedLastMonth > 0) {
            $completedDiffPercentage = round((($completedThisMonth - $completedLastMonth) / $completedLastMonth) * 100);
        } elseif ($completedThisMonth > 0) {
            $completedDiffPercentage = 100;
        }

        // C. Persentase Pendapatan Hari Ini vs Kemarin
        $todayRevenue = Order::whereIn('status', $validStatuses)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');
        $yesterdayRevenue = Order::whereIn('status', $validStatuses)
            ->whereDate('created_at', Carbon::yesterday())
            ->sum('total_amount');

        $revenueDiffPercentage = 0;
        if ($yesterdayRevenue > 0) {
            $revenueDiffPercentage = round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100);
        } elseif ($todayRevenue > 0) {
            $revenueDiffPercentage = 100;
        }

        // Data finansial tambahan untuk dashboard tampilan lama Anda
        $monthlyRevenue = Order::whereIn('status', $validStatuses)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // ==========================================
        // DATA TABEL & GRAFIK (WIDGET)
        // ==========================================

        // Filter: Produk baru 15 hari terakhir
        $recentProducts = Product::with('brand')
            ->where('created_at', '>=', Carbon::now()->subDays(15))
            ->latest()
            ->take(10)
            ->get();

        // Filter: List pesanan pending
        $pendingOrdersList = Order::with('user')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        // Filter: Varian stok rendah
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Grafik Bulanan Pendapatan
        $revenueData = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenueData[] = Order::whereIn('status', $validStatuses)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');
        }

        // Grafik Segmentasi Gender
        $menSales = OrderItem::whereHas('variant.product', function($q) { $q->where('gender_type', 'Men'); })
            ->whereHas('order', function($q) use ($validStatuses) { $q->whereIn('status', $validStatuses); })->sum('quantity');

        $womenSales = OrderItem::whereHas('variant.product', function($q) { $q->where('gender_type', 'Women'); })
            ->whereHas('order', function($q) use ($validStatuses) { $q->whereIn('status', $validStatuses); })->sum('quantity');

        $unisexSales = OrderItem::whereHas('variant.product', function($q) { $q->where('gender_type', 'Unisex'); })
            ->whereHas('order', function($q) use ($validStatuses) { $q->whereIn('status', $validStatuses); })->sum('quantity');

        $totalSales = $menSales + $womenSales + $unisexSales;
        $genderData = [
            $totalSales > 0 ? round(($menSales / $totalSales) * 100) : 0,
            $totalSales > 0 ? round(($womenSales / $totalSales) * 100) : 0,
            $totalSales > 0 ? round(($unisexSales / $totalSales) * 100) : 0,
        ];

        return view('admin.dashboard', compact(
            'totalProducts', 'totalBrands', 'totalVariants', 'totalOrders',
            'pendingOrders', 'completedOrders', 'totalRevenue', 'monthlyRevenue',
            'todayRevenue', 'recentProducts', 'pendingOrdersList', 'lowStockVariants',
            'revenueData', 'genderData',
            'orderDiffPercentage', 'completedDiffPercentage', 'revenueDiffPercentage' // Variabel Live Baru
        ));
    }
}