<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class AdminCustomerController extends Controller
{
    const LEVELS = [
        'Diamond'  => 10000000, // 10 Juta ke atas
        'Platinum' => 5000000,  // 5 Juta ke atas
        'Gold'     => 2000000,  // 2 Juta ke atas
        'Silver'   => 500000,   // 500 Ribu ke atas
        'Bronze'   => 0,        // 0 ke atas
    ];

    public static function getLevel(int $spending): array
    {
        foreach (self::LEVELS as $name => $min) {
            if ($spending >= $min) {
                return match($name) {
                    'Diamond'  => ['name' => 'Diamond',  'color' => 'text-cyan-600',   'bg' => 'bg-cyan-50',   'border' => 'border-cyan-200',   'icon' => 'fa-gem'],
                    'Platinum' => ['name' => 'Platinum', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50', 'border' => 'border-violet-200', 'icon' => 'fa-crown'],
                    'Gold'     => ['name' => 'Gold',     'color' => 'text-amber-600',  'bg' => 'bg-amber-50',  'border' => 'border-amber-200',  'icon' => 'fa-star'],
                    'Silver'   => ['name' => 'Silver',   'color' => 'text-slate-500',  'bg' => 'bg-slate-100', 'border' => 'border-slate-200',  'icon' => 'fa-medal'],
                    default    => ['name' => 'Bronze',   'color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'icon' => 'fa-shield'],
                };
            }
        }
        return ['name' => 'Bronze', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'icon' => 'fa-shield'];
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum(
                ['orders as total_spending' => fn($q) =>
                    $q->whereIn('status', ['Completed', 'Shipped', 'Processing'])
                ],
                'total_amount'
            )
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%')
                       ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('level'), function ($q) use ($request) {
                $ranges = [
                    'Bronze'   => [0,       499999],
                    'Silver'   => [500000,  1999999],
                    'Gold'     => [2000000, 4999999],
                    'Platinum' => [5000000, 9999999],
                    'Diamond'  => [10000000, PHP_INT_MAX],
                ];
                if (isset($ranges[$request->level])) {
                    [$min, $max] = $ranges[$request->level];
                    $q->havingRaw('COALESCE(total_spending, 0) >= ?', [$min]);
                    if ($max !== PHP_INT_MAX) {
                        $q->havingRaw('COALESCE(total_spending, 0) <= ?', [$max]);
                    }
                }
            })
            ->orderByDesc('total_spending');

        $customers = $query->paginate(10)->withQueryString();

        // Hitung distribusi level untuk summary card
        $allSpending = User::where('role', 'customer')
            ->withSum(
                ['orders as total_spending' => fn($q) =>
                    $q->whereIn('status', ['Completed', 'Shipped', 'Processing'])
                ],
                'total_amount'
            )
            ->get()
            ->groupBy(fn($u) => self::getLevel((int)($u->total_spending ?? 0))['name']);

        return view('admin.customers.index', compact('customers', 'allSpending'));
    }

    public function show(User $user)
    {
        $user->loadCount('orders')
             ->loadSum(
                 ['orders as total_spending' => fn($q) =>
                     $q->whereIn('status', ['Completed', 'Shipped', 'Processing'])
                 ],
                 'total_amount'
             );

        $orders = Order::with(['items.variant.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5);

        $level = self::getLevel((int)($user->total_spending ?? 0));

        // Hitung spending menuju level berikutnya
        $nextLevel = null;
        $levels    = array_reverse(self::LEVELS, true); // Bronze → Diamond
        foreach (array_reverse(self::LEVELS, true) as $name => $min) {
            if ((int)($user->total_spending ?? 0) < $min) {
                $nextLevel = ['name' => $name, 'min' => $min];
            }
        }

        return view('admin.customers.show', compact('user', 'orders', 'level', 'nextLevel'));
    }
}