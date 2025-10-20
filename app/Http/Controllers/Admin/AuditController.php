<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with(['user', 'auditable']);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('old_values', 'like', "%{$search}%")
                    ->orWhere('new_values', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $users = User::select('id', 'username', 'email')->get();
        $events = AuditLog::distinct()->pluck('event');
        $auditableTypes = AuditLog::distinct()->pluck('auditable_type');

        // Get statistics
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'this_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AuditLog::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        // Get activity summary
        $activitySummary = AuditLog::select('event', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('event')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.audit.index', compact(
            'auditLogs',
            'users',
            'events',
            'auditableTypes',
            'stats',
            'activitySummary'
        ));
    }

    public function export(Request $request)
    {
        $query = AuditLog::with(['user', 'auditable']);

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('old_values', 'like', "%{$search}%")
                    ->orWhere('new_values', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'audit_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($auditLogs) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Event',
                'User',
                'Auditable Type',
                'Auditable ID',
                'Old Values',
                'New Values',
                'IP Address',
                'User Agent',
                'Created At',
            ]);

            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->event,
                    $log->user ? $log->user->username : 'System',
                    $log->auditable_type,
                    $log->auditable_id,
                    json_encode($log->old_values),
                    json_encode($log->new_values),
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
