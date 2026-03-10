<?php

// Namespace model
namespace App\Models;

// Trait untuk mendukung factory (seeding & testing)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// Class dasar Model Eloquent
use Illuminate\Database\Eloquent\Model;

// Tipe relasi HasMany
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Supplier
 * Merepresentasikan tabel: suppliers
 */
class Supplier extends Model
{
    // Mengaktifkan fitur factory
    use HasFactory, \App\Traits\LogsActivity, \App\Traits\Tenantable, SoftDeletes;

    /**
     * Field yang boleh diisi menggunakan mass assignment
     * (create / update)
     */
    protected $fillable = [
        'name',
        'dir',
        'email',
        'phone',
        'address',
        'user_id',
    ];

    /**
     * Relasi: Supplier memiliki banyak produk
     * (One to Many)
     */
    public function products(): HasMany
    {
        // Foreign key default: supplier_id pada tabel products
        return $this->hasMany(Product::class);
    }
}
