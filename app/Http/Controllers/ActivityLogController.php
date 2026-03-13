<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->search, function($query) use ($request) {
                $query->where('description', 'like', '%'.$request->search.'%')
                      ->orWhere('action', 'like', '%'.$request->search.'%')
                      ->orWhereHas('user', function($q) use ($request) {
                          $q->where('name', 'like', '%'.$request->search.'%');
                      });
            })
            ->when($request->date, function($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view('activity_log.index', compact('logs'));
    }
}
