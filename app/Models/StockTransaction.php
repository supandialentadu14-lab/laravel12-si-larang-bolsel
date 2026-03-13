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
        $loadLockDate = function ($transaction): ?\Carbon\Carbon {
            $userId = \Illuminate\Support\Facades\Auth::id() ?? $transaction->user_id;
            if (! $userId) {
                return null;
            }
            $setting = \App\Models\OpdSetting::where('user_id', $userId)->first();
            if (! $setting || ! $setting->tutup_buku_date) {
                return null;
            }
            return \Carbon\Carbon::parse($setting->tutup_buku_date)->startOfDay();
        };

        $throwLocked = function (\Carbon\Carbon $lockDate, $trxDate) {
            $d = \Carbon\Carbon::parse($trxDate)->toDateString();
            throw new \Exception('TUTUP BUKU AKTIF: Transaksi pada tanggal ' . $d . ' tidak bisa ditambahkan, diubah, atau dihapus karena sudah melewati Batas Tutup Buku.');
        };

        static::creating(function ($transaction) use ($loadLockDate, $throwLocked) {
            $lockDate = $loadLockDate($transaction);
            if (! $lockDate) {
                return;
            }
            $trxDate = \Carbon\Carbon::parse($transaction->date)->startOfDay();
            if ($trxDate->lte($lockDate)) {
                $throwLocked($lockDate, $trxDate);
            }
        });

        static::updating(function ($transaction) use ($loadLockDate, $throwLocked) {
            $lockDate = $loadLockDate($transaction);
            if (! $lockDate) {
                return;
            }
            $newDate = \Carbon\Carbon::parse($transaction->date)->startOfDay();
            $oldDate = $transaction->getOriginal('date')
                ? \Carbon\Carbon::parse($transaction->getOriginal('date'))->startOfDay()
                : null;

            if (($oldDate && $oldDate->lte($lockDate)) || $newDate->lte($lockDate)) {
                $throwLocked($lockDate, $oldDate ?: $newDate);
            }
        });

        static::deleting(function ($transaction) use ($loadLockDate, $throwLocked) {
            $lockDate = $loadLockDate($transaction);
            if (! $lockDate) {
                return;
            }
            $trxDate = \Carbon\Carbon::parse($transaction->date)->startOfDay();
            if ($trxDate->lte($lockDate)) {
                $throwLocked($lockDate, $trxDate);
            }
        });
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
