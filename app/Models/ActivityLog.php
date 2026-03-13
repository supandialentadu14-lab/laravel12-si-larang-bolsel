<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, \App\Traits\Tenantable;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function getFormattedPropertiesAttribute()
    {
        if (!$this->properties || !isset($this->properties['old'])) {
            return null;
        }

        $old = $this->properties['old'];
        $new = $this->properties['new'];
        $changes = [];

        $labels = [
            'name' => 'Nama',
            'sku' => 'Kode Barang',
            'price' => 'Harga',
            'stock' => 'Stok',
            'unit' => 'Satuan',
            'category_id' => 'Kategori',
            'supplier_id' => 'Penyedia',
            'description' => 'Keterangan',
            'min_stock' => 'Stok Minimum',
            'user_id' => 'Pengguna',
            'product_id' => 'Produk',
            'type' => 'Jenis',
            'quantity' => 'Jumlah',
            'nosur' => 'Nomor Surat',
            'notes' => 'Catatan',
            'date' => 'Tanggal',
            'email' => 'Email',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'jumlah_barang' => 'Stok',
        ];

        foreach ($new as $key => $newValue) {
            $oldValue = $old[$key] ?? '-';

            // Skip if values are identical to avoid noise
            if ($oldValue == $newValue) {
                continue;
            }

            $label = $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
            
            // Format values
            if (in_array($key, ['price', 'nilai_total'])) {
                $oldValue = 'Rp ' . number_format((float)($oldValue === '-' ? 0 : $oldValue), 0, ',', '.');
                $newValue = 'Rp ' . number_format((float)$newValue, 0, ',', '.');
            } elseif ($key === 'type') {
                $oldValue = $oldValue === 'in' ? 'Masuk' : ($oldValue === 'out' ? 'Keluar' : $oldValue);
                $newValue = $newValue === 'in' ? 'Masuk' : ($newValue === 'out' ? 'Keluar' : $newValue);
            }

            // Extract name if array
            if (is_array($oldValue)) {
                $oldValue = $oldValue['nama'] ?? ($oldValue['name'] ?? ($oldValue['toko'] ?? json_encode($oldValue)));
            }
            if (is_array($newValue)) {
                $newValue = $newValue['nama'] ?? ($newValue['name'] ?? ($newValue['toko'] ?? json_encode($newValue)));
            }

            // Ensure values are strings for view rendering
            $oldValue = (string)$oldValue;
            $newValue = (string)$newValue;
            
            $changes[] = [
                'label' => $label,
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }

        return $changes;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
