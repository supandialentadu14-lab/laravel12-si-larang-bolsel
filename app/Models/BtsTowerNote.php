<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BtsTowerNote extends Model
{
    protected $fillable = [
        'bts_tower_id',
        'user_id',
        'type',
        'judul',
        'isi',
        'tanggal',
        'biaya',
        'teknisi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function tower(): BelongsTo
    {
        return $this->belongsTo(BtsTower::class, 'bts_tower_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
