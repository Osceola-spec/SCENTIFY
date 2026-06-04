<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailOtp;
use App\Mail\EmailOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function show_login()
    {
        return view('auth.login');
    }

    public function login_auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function show_register()
    {
        return view('auth.register');
    }

    public function register_auth(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|max:20|unique:users,username',
            // Require confirmation field named `password_confirmation` in the form
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 1. Logika pembuatan username otomatis & aman dari limit 20 karakter
        if ($request->filled('username')) {
            $username = (string) $request->input('username');
        } else {
            // Ambil bagian depan email sebelum '@' (Contoh: ckurniawan03)
            $username = explode('@', $validated['email'])[0];
        }

        // Potong paksa maksimal 20 karakter sesuai limit database terbaru kamu
        $username = mb_substr($username, 0, 20);

        // Antisipasi jika username otomatis tersebut ternyata sudah terdaftar di database
        $originalUsername = $username;
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $suffix = (string) $count;
            // Potong teks asli agar ketika digabung angka, totalnya tetap maksimal 20 karakter
            $username = mb_substr($originalUsername, 0, 20 - strlen($suffix)) . $suffix;
            $count++;
        }

        // 2. Memecah input 'name' menjadi 'first_name' dan 'last_name' karena kolom 'name' sudah di-drop
        $nameParts = explode(' ', trim($validated['name']));
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : null;

        // 3. Jangan buat user dulu — simpan data pendaftaran di session sampai email terverifikasi
        $pending = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => $username,  
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => 'customer',
        ];

        $request->session()->put('pending_registration', $pending);

        // Buat kode OTP yang dikaitkan ke email (user_id belum ada)
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        EmailOtp::create([
            'user_id' => null,
            'email' => $validated['email'],
            'code_hash' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Kirim email OTP (queued)
        Mail::to($validated['email'])->queue(new EmailOtpMail((object)$pending, $code));

        // Simpan email sementara di session untuk verifikasi
        $request->session()->put('pending_registration_email', $validated['email']);

        return redirect()->route('verify.email');
    }

    public function show_verify(Request $request)
    {
        if (! $request->session()->has('pending_registration_email')) {
            return redirect()->route('register')->withErrors(['email' => 'Silakan daftar terlebih dahulu.']);
        }

        return view('auth.verify_email');
    }

    public function verify_email_post(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $email = $request->session()->get('pending_registration_email');
        if (! $email) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi tidak ditemukan.']);
        }

        $otp = EmailOtp::where('email', $email)->latest()->first();
        if (! $otp || Carbon::now()->greaterThan($otp->expires_at) || ! Hash::check($request->input('code'), $otp->code_hash)) {
            return back()->withErrors(['code' => 'Kode tidak valid atau telah kadaluarsa.']);
        }

        // Ambil data pendaftaran dari session dan buat user
        $pending = $request->session()->get('pending_registration');
        if (! $pending) {
            return redirect()->route('register')->withErrors(['email' => 'Data pendaftaran tidak ditemukan.']);
        }

        $user = User::create($pending + ['email_verified_at' => Carbon::now()]);

        // Hapus OTP dan session pending
        $otp->delete();
        $request->session()->forget(['pending_registration', 'pending_registration_email']);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function resend_otp(Request $request)
    {
        $email = $request->session()->get('pending_registration_email');
        if (! $email) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi tidak ditemukan.']);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        EmailOtp::create([
            'user_id' => null,
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        Mail::to($email)->queue(new EmailOtpMail((object)$request->session()->get('pending_registration'), $code));

        return back()->with('status', 'Kode OTP baru telah dikirimkan.');
    }
}