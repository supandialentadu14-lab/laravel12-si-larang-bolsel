<?php

// Namespace model
namespace App\Models;

// Trait untuk mendukung factory (seeding & testing)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// Class dasar Model Eloquent
use Illuminate\Database\Eloquent\Model;

// Tipe relasi BelongsTo
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model StockTransaction
 * Merepresentasikan tabel: stock_transactions
 */
class StockTransaction extends Model
{
    // Mengaktifkan fitur factory
    use HasFactory, \App\Traits\LogsActivity, \App\Traits\Tenantable, SoftDeletes;

    protected static function booted()
    {
        $guard = function ($transaction) {
            $userId = \Illuminate\Support\Facades\Auth::id() ?? $transaction->user_id;
            if ($userId) {
                $setting = \App\Models\OpdSetting::where('user_id', $userId)->first();
                if ($setting && $setting->tutup_buku_date) {
                    if (\Carbon\Carbon::parse($transaction->date)->lte(\Carbon\Carbon::parse($setting->tutup_buku_date))) {
                        throw new \Exception('TUTUP BUKU AKTIF: Transaksi pada tanggal ' . $transaction->date . ' tidak bisa ditambahkan, diubah, atau dihapus karena sudah melewati Batas Tutup Buku.');
                    }
                }
            }
        };

        static::creating($guard);
        static::updating($guard);
        static::deleting($guard);
    }

    /**
     * Field yang boleh diisi menggunakan mass assignment
     * (create / update)
     */
    protected $fillable = [
        'product_id', // ID produk yang ditransaksikan
        'user_id',    // ID user yang melakukan transaksi
        'type',       // Jenis transaksi: in (masuk) / out (keluar)
        'quantity',   // Jumlah barang
        'nosur',   // Nomor Surat
        'notes',      // Catatan tambahan
        'date',       // Tanggal transaksi
    ];

    /**
     * Casting otomatis tipe data
     * Kolom 'date' akan otomatis menjadi object Carbon
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relasi: Transaksi ini milik satu produk
     * (Many to One)
     */
    public function product(): BelongsTo
    {
        // Foreign key default: product_id
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi: Transaksi ini dilakukan oleh satu user
     * (Many to One)
     */
    public function user(): BelongsTo
    {
        // Foreign key default: user_id
        return $this->belongsTo(User::class);
    }
}
