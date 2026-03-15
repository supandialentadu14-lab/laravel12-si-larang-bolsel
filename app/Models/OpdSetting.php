<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdSetting extends Model
{
    use HasFactory, \App\Traits\Tenantable, \App\Traits\LogsActivity;

    protected $fillable = [
        'user_id',
        'nama_opd', 'singkatan_opd', 'tutup_buku_date',
        'alamat_opd',
        'kepala_nama', 'kepala_pangkat', 'kepala_jabatan', 'kepala_nip',
        'pengurus_nama', 'pengurus_pangkat', 'pengurus_jabatan', 'pengurus_nip', 'pengurus_sk',
        'pengguna_nama', 'pengguna_pangkat', 'pengguna_jabatan', 'pengguna_nip',
    ];

    public function setSingkatanOpdAttribute($value)
    {
        $this->attributes['singkatan_opd'] = strtoupper($value);
    }
}
