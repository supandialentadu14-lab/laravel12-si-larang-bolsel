<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $dateFilter = $request->date;

        // 1. Get unique dates that have logs, considering the filters
        $datePaginator = ActivityLog::query()
            ->selectRaw('DATE(created_at) as log_date', [])
            ->when($search, function($query) use ($search) {
                $query->where('description', 'like', '%'.$search.'%')
                      ->orWhere('action', 'like', '%'.$search.'%')
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', '%'.$search.'%');
                      });
            })
            ->when($dateFilter, function($query) use ($dateFilter) {
                $query->whereDate('created_at', $dateFilter);
            })
            ->groupBy('log_date')
            ->orderBy('log_date', 'desc')
            ->paginate(1) // One day per page
            ->withQueryString();

        // 2. Fetch logs for the current date(s) in the paginator
        $currentDates = $datePaginator->items();
        $logs = collect();

        if (!empty($currentDates)) {
            $targetDate = $currentDates[0]->log_date;
            
            $logs = ActivityLog::with('user')
                ->whereDate('created_at', $targetDate)
                ->when($search, function($query) use ($search) {
                    $query->where('description', 'like', '%'.$search.'%')
                          ->orWhere('action', 'like', '%'.$search.'%')
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          });
                })
                ->latest()
                ->get();
        }

        return view('activity_log.index', [
            'logs' => $logs,
            'datePaginator' => $datePaginator
        ]);
    }
}
