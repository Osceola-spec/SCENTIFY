<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    // 1. Mengirim user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Menerima data balikan dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cek apakah user ini sudah pernah login/terdaftar sebelumnya
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Jika email sudah ada, update google_id-nya dan login
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
                Auth::login($user);
            } else {
                // Jika belum ada, buat akun baru secara otomatis
                $baseUsername = strtolower(str_replace(' ', '', $googleUser->name));
                $username = $baseUsername;
                $i = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $i++;
                }

                $nameParts = explode(' ', $googleUser->name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                $newUser = User::create([
                    'name' => $googleUser->name,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $username,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'customer',
                ]);
                Auth::login($newUser);
            }

            // Arahkan ke halaman utama toko setelah sukses
            return redirect()->route('shop')->with('success', 'Successfully logged in with Google!');

        } catch (Exception $e) {
            Log::error('Google login callback failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->with('error', 'Failed to login with Google. Please try again.');
        }
    }
}