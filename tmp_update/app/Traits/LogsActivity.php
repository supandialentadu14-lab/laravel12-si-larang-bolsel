<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $model->logActivity(strtoupper($event));
            });
        }
    }

    public function logActivity($action)
    {
        try {
            $properties = null;

            if ($action === 'UPDATED') {
                $properties = [
                    'old' => array_intersect_key($this->getOriginal(), $this->getDirty()),
                    'new' => $this->getDirty(),
                ];
            }

            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'model_type' => get_class($this),
                'model_id'   => $this->id,
                'description' => $this->getActivityDescription($action),
                'properties' => $properties,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Logging should never break the main operation
            \Illuminate\Support\Facades\Log::warning('ActivityLog failed: ' . $e->getMessage());
        }
    }

    protected function getActivityDescription($action)
    {
        $name = $this->name ?? $this->id;
        return "{$action} " . class_basename($this) . ": {$name}";
    }
}
