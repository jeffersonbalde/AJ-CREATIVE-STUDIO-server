<?php

namespace App\Http\Controllers;

use App\Models\CustomerTimeLog;
use App\Models\Customer;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    /**
     * Display a listing of customer time logs
     */
    public function index(Request $request)
    {
        $query = CustomerTimeLog::with('customer');

        // Filter by customer
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('logged_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('logged_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'logged_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $timeLogs = $query->paginate($perPage);

        // Format response
        $timeLogsData = $timeLogs->getCollection()->map(function ($log) {
            return [
                'id' => $log->id,
                'customer_id' => $log->customer_id,
                'customer_name' => $log->customer->name ?? 'N/A',
                'customer_email' => $log->customer->email ?? 'N/A',
                'action' => $log->action,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'logged_at' => $log->logged_at,
                'created_at' => $log->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'time_logs' => $timeLogsData->all(),
            'pagination' => [
                'current_page' => $timeLogs->currentPage(),
                'last_page' => $timeLogs->lastPage(),
                'per_page' => $timeLogs->perPage(),
                'total' => $timeLogs->total(),
                'from' => $timeLogs->firstItem(),
                'to' => $timeLogs->lastItem(),
            ],
        ]);
    }

    /**
     * Get time logs for a specific customer
     */
    public function getCustomerLogs($customerId, Request $request)
    {
        $customer = Customer::findOrFail($customerId);

        $query = $customer->timeLogs();

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('logged_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('logged_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'logged_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $timeLogs = $query->paginate($perPage);

        $timeLogsData = $timeLogs->getCollection()->map(function ($log) {
            return [
                'id' => $log->id,
                'action' => $log->action,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'logged_at' => $log->logged_at,
                'created_at' => $log->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
            ],
            'time_logs' => $timeLogsData->all(),
            'pagination' => [
                'current_page' => $timeLogs->currentPage(),
                'last_page' => $timeLogs->lastPage(),
                'per_page' => $timeLogs->perPage(),
                'total' => $timeLogs->total(),
                'from' => $timeLogs->firstItem(),
                'to' => $timeLogs->lastItem(),
            ],
        ]);
    }
}
