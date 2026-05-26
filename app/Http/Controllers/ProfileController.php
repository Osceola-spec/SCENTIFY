<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminCustomerController; // <-- Import controller admin
use App\Models\Order; // <-- Import model Order jika belum

class ProfileController extends Controller
{
    public function show_profile()
    {
        $user = Auth::user();
        
        // Hitung total spending user ini dengan kondisi status yang sama seperti di Admin side
        $totalSpending = Order::where('user_id', $user->id)
            ->whereIn('status', ['Completed', 'Shipped', 'Processing'])
            ->sum('total_amount');

        // Ambil data level (nama, warna, bg, icon) secara dinamis
        $level = AdminCustomerController::getLevel((int)$totalSpending);

        return view('auth.profile', compact('user', 'level'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Logged out successfully');
    }

    public function update_profile(Request $request)
{
    $user = auth()->user(); // Ambil data user yang sedang login

    // Perbarui validasi di bawah ini sesuai input baru
    $validated = $request->validate([
        'username'        => 'required|string|max:20|unique:users,username,' . $user->id, // Mencegah username kembar dengan user lain
        'first_name'      => 'required|string|max:50',
        'last_name'       => 'nullable|string|max:50',
        'bio'             => 'nullable|string|max:500',
        'phone'           => 'nullable|string|max:20',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Logika upload foto profil tetap sama
    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture && file_exists(public_path('images/' . $user->profile_picture))) {
            unlink(public_path('images/' . $user->profile_picture));
        }

        $file = $request->file('profile_picture');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $validated['profile_picture'] = $filename;
    }

    // Proses update ke database
    $user->update($validated);

    return redirect()->back()->with('success', 'Profile updated successfully');
}
}