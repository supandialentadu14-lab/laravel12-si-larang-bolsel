<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    use \App\Traits\Tenantable, \App\Traits\LogsActivity;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
