<?php

// Menentukan namespace (lokasi file dalam struktur Laravel)
namespace App\Http\Controllers\Auth;

// Mengimpor Controller utama Laravel
use App\Http\Controllers\Controller;

// Mengimpor Model User untuk menyimpan data user ke database
use App\Models\User;

// Event yang dijalankan ketika user berhasil registrasi
use Illuminate\Auth\Events\Registered;

// Digunakan untuk tipe return redirect
use Illuminate\Http\RedirectResponse;

// Digunakan untuk menangkap data request dari form
use Illuminate\Http\Request;

// Facade Auth untuk proses login otomatis
use Illuminate\Support\Facades\Auth;

// Facade Hash untuk mengenkripsi password
use Illuminate\Support\Facades\Hash;

// Digunakan untuk aturan validasi password bawaan Laravel
use Illuminate\Validation\Rules;

// Digunakan untuk tipe return View (tampilan)
use Illuminate\View\View;

// Controller untuk menangani proses registrasi user
class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi
     */
    public function create(): View
    {
        // Menampilkan view register yang berada di:
        // resources/views/auth/register.blade.php
        return view('auth.register');
    }

    /**
     * Menangani proses pendaftaran user baru
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form registrasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nama_opd' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        // Generate password acak 10 karakter
        $randomPassword = \Illuminate\Support\Str::random(10);

        // Membuat user baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_opd' => $request->nama_opd,
            'password' => Hash::make($randomPassword),
            'role' => 'staff',
        ]);

        // Memicu event Registered
        event(new Registered($user));

        // Kirim Email Credentials
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserCredentialsMail($user, $randomPassword));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email registrasi: ' . $e->getMessage());
        }

        // Alihkan ke halaman login dengan pesan sukses
        return redirect(route('login'))
            ->with('success_message', 'Registrasi Berhasil! Silakan cek email Anda (' . $user->email . ') untuk melihat detail Akun (Username & Password) untuk login.');
    }
}
