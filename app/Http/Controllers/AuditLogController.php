<?php

namespace App\Http\Controllers;

use App\Exports\AuditLogsExport;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return view('audit-logs.index', [
            'logs' => $query->paginate(25)->withQueryString(),
            'users' => User::orderBy('name')->get(),
            'actions' => AuditLog::select('action')->distinct()->orderBy('action')->pluck('action'),
            'modelTypes' => AuditLog::select('model_type')
                ->whereNotNull('model_type')
                ->distinct()
                ->orderBy('model_type')
                ->pluck('model_type'),
        ]);
    }

    public function export()
    {
        return Excel::download(
            new AuditLogsExport,
            'audit-logs.xlsx'
        );
    }
}