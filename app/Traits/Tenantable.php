<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Tenantable
{
    /**
     * Boot the trait for a model.
     *
     * @return void
     */
    protected static function bootTenantable()
    {
        // 1. Add global scope to automatically filter by the currently authenticated user
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check()) {
                // Get the table name to avoid ambiguous column errors in JOINs
                $table = $builder->getModel()->getTable();
                $builder->where($table . '.user_id', Auth::id());
            }
        });

        // 2. Automatically set user_id when creating a new record
        static::creating(function (Model $model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }
}
