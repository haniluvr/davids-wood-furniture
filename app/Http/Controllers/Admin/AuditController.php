<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AuditLog::with(['admin', 'user']);

            // Apply filters
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('category')) {
                $query->whereIn('action', $this->getActionsByCategory($request->category));
            }

            if ($request->filled('model')) {
                $query->where('model', $request->model);
            }

            if ($request->filled('user_type')) {
                $query->where('user_type', $request->user_type);
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
                    $q->where('action', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhere('user_agent', 'like', "%{$search}%")
                        ->orWhere('model_id', 'like', "%{$search}%")
                        ->orWhereHas('admin', function ($adminQuery) use ($search) {
                            $adminQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

            // Get filter options
            $adminUsers = Admin::select('id', 'first_name', 'last_name', 'email', 'role')
                ->orderBy('first_name')
                ->get();

            // Get all possible actions (both from database and predefined list)
            $dbActions = AuditLog::distinct()->whereNotNull('action')->pluck('action')->toArray();
            $allActions = array_unique(array_merge($dbActions, $this->getAllPossibleActions()));
            sort($allActions);
            $actions = collect($allActions);

            // Get all possible models (both from database and predefined list)
            $dbModels = AuditLog::distinct()->whereNotNull('model')->pluck('model')->toArray();
            $allModels = array_unique(array_merge($dbModels, $this->getAllPossibleModels()));
            sort($allModels);
            $models = collect($allModels);

            // Get statistics
            $stats = [
                'total' => AuditLog::count(),
                'today' => AuditLog::whereDate('created_at', today())->count(),
                'this_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => AuditLog::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            ];

            // Get activity summary
            $activitySummary = AuditLog::select('action', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [now()->subDays(30), now()])
                ->whereNotNull('action')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            return view('admin.audit.index', compact(
                'auditLogs',
                'adminUsers',
                'actions',
                'models',
                'stats',
                'activitySummary'
            ));
        } catch (\Exception $e) {
            \Log::error('Audit index error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->to(admin_route('dashboard'))->withErrors(['error' => 'An error occurred while loading the audit log: '.$e->getMessage()]);
        }
    }

    protected function getActionsByCategory(string $category): array
    {
        return match ($category) {
            'security' => [
                'login',
                'logout',
                'password_changed',
                'admin_user.created',
                'admin_user.deleted',
                'admin_user.role_changed',
                'admin_user.deactivated',
                'admin_user.permissions_updated',
                'admin_user.permissions_reset',
            ],
            'orders' => [
                'order.created',
                'order.updated',
                'order.status_updated',
                'order.deleted',
                'order.refund_issued',
                'order.approved',
                'order.rejected',
                'order.fulfillment_status_updated',
            ],
            'inventory' => [
                'inventory.adjusted',
                'inventory.added',
                'inventory.removed',
            ],
            'users' => [
                'customer.created',
                'customer.updated',
                'customer.deleted',
                'customer.suspended',
                'customer.unsuspended',
                'customer.email_verified',
                'customer.email_unverified',
                'customer.password_reset',
                'admin_user.created',
                'admin_user.deleted',
            ],
            default => []
        };
    }

    /**
     * Get all possible action types that can be logged.
     */
    protected function getAllPossibleActions(): array
    {
        return [
            // Orders
            'order.created',
            'order.updated',
            'order.deleted',
            'order.status_updated',
            'order.approved',
            'order.rejected',
            'order.refund_issued',
            'order.fulfillment_status_updated',

            // Customers
            'customer.created',
            'customer.updated',
            'customer.deleted',
            'customer.suspended',
            'customer.unsuspended',
            'customer.email_verified',
            'customer.email_unverified',
            'customer.password_reset',

            // Admin Users
            'admin_user.created',
            'admin_user.deleted',
            'admin_user.permissions_updated',
            'admin_user.permissions_reset',

            // Inventory
            'inventory.adjusted',
            'inventory.added',
            'inventory.removed',

            // Shipping Methods
            'shipping_method.created',
            'shipping_method.updated',
            'shipping_method.deleted',
            'shipping_method.status_toggled',
            'shipping_method.reordered',

            // Payment Gateways
            'payment_gateway.created',
            'payment_gateway.updated',
            'payment_gateway.deleted',
            'payment_gateway.status_toggled',
            'payment_gateway.mode_toggled',
            'payment_gateway.reordered',

            // Settings
            'settings.general_updated',
            'settings.email_updated',

            // Returns & Repairs
            'return_repair.created',
            'return_repair.approved',
            'return_repair.rejected',
            'return_repair.received',
            'return_repair.refund_processed',
            'return_repair.completed',

            // Authentication (if implemented)
            'login',
            'logout',
            'password_changed',
        ];
    }

    /**
     * Get all possible model types that can be logged.
     */
    protected function getAllPossibleModels(): array
    {
        return [
            'App\Models\Order',
            'App\Models\User',
            'App\Models\Admin',
            'App\Models\Product',
            'App\Models\ShippingMethod',
            'App\Models\PaymentGateway',
            'App\Models\ReturnRepair',
            'App\Models\Category',
            'App\Models\InventoryMovement',
        ];
    }

    public function export(Request $request)
    {
        $query = AuditLog::with(['admin', 'user']);

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('category')) {
            $query->whereIn('action', $this->getActionsByCategory($request->category));
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
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
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%")
                    ->orWhere('model_id', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($adminQuery) use ($search) {
                        $adminQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
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
                'Timestamp',
                'User Type',
                'User Name',
                'User Email',
                'User Role',
                'Action',
                'Description',
                'Model',
                'Model ID',
                'Old Values',
                'New Values',
                'IP Address',
                'User Agent',
                'Created At',
            ]);

            // CSV data
            foreach ($auditLogs as $log) {
                $userName = 'System';
                $userEmail = '';
                $userRole = '';

                if ($log->user_type === 'admin' && $log->admin) {
                    $userName = $log->admin->full_name;
                    $userEmail = $log->admin->email;
                    $userRole = $log->admin->role ?? '';
                } elseif ($log->user_type === 'user' && $log->user) {
                    $userName = $log->user->first_name.' '.$log->user->last_name;
                    $userEmail = $log->user->email ?? '';
                }

                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_type,
                    $userName,
                    $userEmail,
                    $userRole,
                    $log->action,
                    $log->description,
                    $log->model,
                    $log->model_id,
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

    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = Admin::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
        })
            ->select('id', 'first_name', 'last_name', 'email', 'role')
            ->orderBy('first_name')
            ->limit(20)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'role' => $user->role,
                ];
            });

        return response()->json($users);
    }
}
