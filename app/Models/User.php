<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Digunakan jika ingin fitur verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Model autentikasi bawaan Laravel
use Illuminate\Notifications\Notifiable; // Untuk fitur notifikasi (email, dll)

/**
 * Model User
 * Digunakan untuk autentikasi (login, logout, dll)
 * Terhubung dengan tabel: users
 */
class User extends Authenticatable
{
    /**
     * Trait:
     * - HasFactory → untuk seeding & testing
     * - Notifiable → agar user bisa menerima notifikasi
     */
    use HasFactory, Notifiable, \App\Traits\LogsActivity;

    /**
     * Field yang boleh diisi menggunakan mass assignment
     * (create / update)
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'role',
        'last_seen_at',
        'is_active',
        'permissions',
    ];

    /**
     * Method untuk mengecek apakah user adalah admin
     * Bisa dipanggil dengan:
     * auth()->user()->isAdmin();
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user aktif (tidak dinonaktifkan oleh admin)
     */
    public function isActiveUser(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Cek apakah user sedang online (last_seen_at dalam 3 menit terakhir)
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(3));
    }

    /**
     * Field yang disembunyikan saat model diubah menjadi array / JSON
     * (contoh: API response)
     */
    protected $hidden = [
        'password',       // Password tidak boleh terlihat
        'remember_token', // Token "remember me"
    ];

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Casting tipe data otomatis
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_seen_at'      => 'datetime',
            'is_active'         => 'boolean',
            'permissions'       => 'array',
        ];
    }
}
