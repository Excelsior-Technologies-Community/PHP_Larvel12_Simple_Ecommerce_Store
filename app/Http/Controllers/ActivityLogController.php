<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $action = $request->action;

        $logs = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->when($action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->latest()
            ->paginate(20);

        $actions = ActivityLog::distinct('action')->pluck('action');

        return view('admin.activity-logs.index', compact('logs', 'actions', 'search', 'action'));
    }
}
