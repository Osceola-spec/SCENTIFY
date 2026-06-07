<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // Urutan status yang valid — disesuaikan dengan skema Database (Paid menggantikan Processing)
    const STATUS_ORDER = [
        'Pending'   => 0,
        'Paid'      => 1, // Database Scentify menggunakan 'Paid'
        'Shipped'   => 2,
        'Completed' => 3,
        'Cancelled' => 4,
    ];

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            // Jika filter dari view bernilai 'Processing', kita query menggunakan 'Paid'
            $searchStatus = $request->status === 'Processing' ? 'Paid' : $request->status;
            $query->where('status', $searchStatus);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('username', 'LIKE', "%{$search}%")
                         ->orWhere('first_name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // Modifikasi status 'Paid' menjadi 'Processing' saat dikirim ke View agar seragam dengan UI
        $orders->getCollection()->transform(function ($order) {
            if ($order->status === 'Paid') {
                $order->status = 'Processing';
            }
            return $order;
        });

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.variant.product', 'user'])->findOrFail($id);
        
        // Memetakan 'Paid' ke 'Processing' saat me-render view agar select option terpilih dengan benar
        if ($order->status === 'Paid') {
            $order->status = 'Processing';
        }

        // Tentukan status apa saja yang boleh dipilih (hanya yang lebih tinggi)
        // Gunakan nama status sementara 'Processing' untuk menghitung level
        $mappedStatus = $order->status === 'Processing' ? 'Paid' : $order->status;
        $currentLevel  = self::STATUS_ORDER[$mappedStatus] ?? 0;
        $allowedStatuses = [];

        // Cancelled hanya bisa dari Pending atau Processing (Paid)
        foreach (self::STATUS_ORDER as $status => $level) {
            if ($status === 'Cancelled') {
                if ($currentLevel <= 1) { // Pending atau Processing(Paid)
                    $allowedStatuses[] = $status;
                }
                continue;
            }
            if ($level > $currentLevel) {
                // Kembalikan ke format Processing agar view mendeteksinya
                $allowedStatuses[] = $status === 'Paid' ? 'Processing' : $status;
            }
        }

        return view('admin.orders.show', compact('order', 'allowedStatuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $currentLevel = self::STATUS_ORDER[$order->status] ?? 0;
        
        // Konversi 'Processing' dari View menjadi 'Paid' untuk disimpan di Database
        $newStatus    = $request->status === 'Processing' ? 'Paid' : $request->status;
        $newLevel     = self::STATUS_ORDER[$newStatus] ?? -1;

        // 1. Validasi input
        $request->validate([
            // Tetap izinkan 'Processing' karena dikirim oleh form UI
            'status' => 'required|in:Pending,Processing,Paid,Shipped,Completed,Cancelled',
            'tracking_number' => $newStatus === 'Shipped'
                ? 'required|string|max:100'
                : 'nullable|string|max:100',
        ], [
            'status.required'           => 'Status is required.',
            'status.in'                 => 'Invalid status.',
            'tracking_number.required'  => 'Tracking number is required when status is changed to Shipped.',
        ]);

        // 2. Status tidak boleh sama
        if ($newStatus === $order->status) {
            $uiStatus = $order->status === 'Paid' ? 'Processing' : $order->status;
            return redirect()->back()->with('error', 'Order status is already ' . $uiStatus . '.');
        }

        // 3. Tidak boleh mundur (reverse)
        $isCancelAllowed = $newStatus === 'Cancelled' && $currentLevel <= 1;

        if (!$isCancelAllowed && $newLevel <= $currentLevel) {
            $uiStatus = $order->status === 'Paid' ? 'Processing' : $order->status;
            return redirect()->back()->with('error',
                'Status cannot be reverted. The order is already in the ' . $uiStatus . ' stage.'
            );
        }

        // 4. Cancelled tidak boleh dari Shipped/Completed
        if ($newStatus === 'Cancelled' && $currentLevel >= 2) {
            return redirect()->back()->with('error',
                'Orders that have been shipped or completed cannot be cancelled.'
            );
        }

        // 5. Update Database (Akan menyimpan 'Paid', bukan 'Processing')
        $order->update([
            'status'          => $newStatus,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number,
        ]);

        // Feedback ke user menggunakan nama 'Processing' agar selaras dengan Front End
        $feedbackStatus = $request->status === 'Processing' ? 'Processing' : $newStatus;
        return redirect()->back()->with('success', 'Order status successfully updated to ' . $feedbackStatus . '.');
    }
}