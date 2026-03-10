<?php

// Menentukan namespace model
namespace App\Models;

// Trait untuk mendukung fitur factory (digunakan saat seeding/testing)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// Class dasar Model Eloquent
use Illuminate\Database\Eloquent\Model;

// Digunakan untuk menentukan tipe relasi HasMany
use Illuminate\Database\Eloquent\Relations\HasMany;

// Model Category merepresentasikan tabel 'categories' di database
class Category extends Model
{
    // Mengaktifkan fitur factory
    use HasFactory, \App\Traits\LogsActivity, \App\Traits\Tenantable, SoftDeletes;

    /**
     * Menentukan kolom yang boleh diisi secara mass assignment
     * (digunakan saat create() atau update())
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id'
    ];

    /**
     * Relasi: Satu kategori memiliki banyak produk
     * (One to Many Relationship)
     */
    public function products(): HasMany
    {
        // hasMany(ModelTujuan::class)
        // Artinya satu category bisa memiliki banyak product
        return $this->hasMany(Product::class);
    }
}
