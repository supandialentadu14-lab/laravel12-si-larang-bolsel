<?php

// Menentukan namespace model
namespace App\Models;

// Trait untuk mendukung fitur factory (seeding/testing)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// Class dasar Model Eloquent
use Illuminate\Database\Eloquent\Model;

// Model Product merepresentasikan tabel 'products'
class Product extends Model
{
    // Mengaktifkan fitur factory
    use HasFactory, \App\Traits\LogsActivity, \App\Traits\Tenantable, SoftDeletes;

    /**
     * Kolom yang boleh diisi menggunakan mass assignment
     * (create() / update())
     */
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'price',
        'stock',
        'unit',
        'category_id',
        'supplier_id',
        'description',
        'min_stock',
        'user_id',
    ];

    /**
     * Relasi: Produk milik satu kategori
     * (Many to One)
     */
    public function category()
    {
        // belongsTo(ModelTujuan::class)
        // Foreign key default: category_id
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Produk milik satu supplier
     * (Many to One)
     */
    public function supplier()
    {
        // Foreign key default: supplier_id
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi: Produk memiliki banyak transaksi
     * (One to Many)
     */
    public function transactions()
    {
        // ⚠ Pastikan nama model sesuai.
        // Jika tabelnya stock_transactions, seharusnya:
        return $this->hasMany(StockTransaction::class);
    }

    /**
     * Accessor: Menghitung stok berdasarkan transaksi
     * Bisa dipanggil dengan:
     * $product->calculated_stock
     */
    public function getCalculatedStockAttribute()
    {
        // Saldo akhir mengikuti logika laporan persediaan:
        // - tipe 'saldo' => set saldo langsung ke jumlahnya (saldo awal)
        // - tipe 'in'    => tambah
        // - tipe 'out'   => kurang
        $balance = 0;
        $txs = $this->transactions()
            ->orderBy('date')
            ->orderBy('id')
            ->get(['type', 'quantity']);

        foreach ($txs as $t) {
            if ($t->type === 'saldo') {
                $balance = (int) $t->quantity;
            } elseif ($t->type === 'in') {
                $balance += (int) $t->quantity;
            } elseif ($t->type === 'out') {
                $balance -= (int) $t->quantity;
            }
        }

        return $balance;
    }

    public function getMinStockAttribute($value)
    {
        return max(1, (int)($value ?? 1));
    }
}
