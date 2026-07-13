<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BtsTowerPhoto extends Model
{
    protected $fillable = ['bts_tower_id', 'user_id', 'path', 'caption', 'sort_order'];

    public function tower(): BelongsTo
    {
        return $this->belongsTo(BtsTower::class, 'bts_tower_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
