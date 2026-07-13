<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BtsAlert extends Model
{
    protected $fillable = ['bts_tower_id', 'user_id', 'type', 'title', 'message', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function tower(): BelongsTo
    {
        return $this->belongsTo(BtsTower::class, 'bts_tower_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
