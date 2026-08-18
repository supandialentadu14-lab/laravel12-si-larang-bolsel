<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BtsTowerPhoto;
use App\Models\BtsAlert;

class BtsTower extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_bts',
        'nama_bts',
        'provider',
        'nama_perusahaan',
        'kecamatan',
        'desa',
        'alamat',
        'latitude',
        'longitude',
        'tinggi_tower',
        'tipe_tower',
        'kondisi',
        'status_operasional',
        'tahun_dibangun',
        'foto',
        'keterangan',
        'user_id',
        'coverage_radius',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'tinggi_tower' => 'decimal:2',
        'coverage_radius' => 'decimal:2',
    ];

    public static array $kecamatanList = [
        'Bolaang Uki',
        'Helumo',
        'Pinolosian',
        'Pinolosian Tengah',
        'Pinolosian Timur',
        'Posigadan',
        'Tomini',
    ];

    public static array $providerList = [
        'Telkomsel', 'Indosat', 'XL Axiata', 'Tri (3)', 'Smartfren', 'Lainnya',
    ];

    public static array $tipeTowerList = [
        'Self Supporting Tower (SST)', 'Guyed Mast', 'Monopole', 'Microcell/Pole', 'Rooftop',
    ];

    public static array $kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Perlu Perbaikan'];

    public static array $statusList = ['Aktif', 'Tidak Aktif', 'Maintenance'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(BtsTowerNote::class)->latest();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(BtsTowerPhoto::class)->orderBy('sort_order');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(BtsAlert::class)->latest();
    }

    public function unreadAlerts()
    {
        return $this->alerts()->where('is_read', false);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }
}
