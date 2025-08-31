<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActionLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ActionLogController extends Controller
{
    public function index(Request $request)
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized');
        }

        $query = ActionLog::with('user');

        // Apply filters
        if ($request->filled('user_search')) {
            $query->userSearch($request->user_search);
        }

        if ($request->filled('action_search')) {
            $query->actionSearch($request->action_search);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('category')) {
            $query->category($request->category);
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Enhanced sorting
        $sortColumn = $request->get('sort_column', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = ['created_at', 'user_name', 'user_role', 'action_category', 'action_type', 'status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortColumn, $sortDirection);

        $logs = $query->paginate(25)->appends($request->query());

        // Get filter options
        $users = User::select('user_id', 'name')->orderBy('name')->get();
        $categories = ActionLog::distinct()->pluck('action_category')->filter();
        $actionTypes = ActionLog::distinct()->pluck('action_type')->filter();

        return view('admin.action-logs.index', compact('logs', 'users', 'categories', 'actionTypes', 'sortColumn', 'sortDirection'));
    }

    public function getUserSuggestions(Request $request)
    {
        $search = $request->get('q');

        $users = User::where('name', 'like', '%' . $search . '%')
            ->select('user_id', 'name')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    public function getActionSuggestions(Request $request)
    {
        $search = $request->get('q');

        $actions = ActionLog::where('action_type', 'like', '%' . $search . '%')
            ->orWhere('action_description', 'like', '%' . $search . '%')
            ->distinct()
            ->limit(10)
            ->get(['action_type', 'action_description']);

        $suggestions = [];
        foreach ($actions as $action) {
            $suggestions[] = $action->action_type;
            if (!in_array($action->action_description, $suggestions)) {
                $suggestions[] = $action->action_description;
            }
        }

        return response()->json(array_unique($suggestions));
    }

    public function cleanup()
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized');
        }

        $archivedCount = ActionLog::cleanupOldLogs();

        return back()->with('success', "Archived and cleaned up {$archivedCount} old log entries.");
    }

    public function show(ActionLog $actionLog)
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized');
        }

        $actionLog->load('user');

        return view('admin.action-logs.show', compact('actionLog'));
    }

    /**
     * List archived log files
     */
    public function archives()
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized');
        }

        $archivePath = storage_path('app/action_logs_archives');
        $archives = [];

        if (is_dir($archivePath)) {
            $files = glob($archivePath . '/*.json');

            foreach ($files as $file) {
                $fileName = basename($file);
                $fileSize = filesize($file);
                $fileDate = date('Y-m-d H:i:s', filemtime($file));

                // Extract date from filename
                if (preg_match('/action_logs_archive_(\d{4})_(\d{2})_(\d{2})_(\d{2})_(\d{2})_(\d{2})\.json/', $fileName, $matches)) {
                    $archiveDate = "{$matches[1]}-{$matches[2]}-{$matches[3]} {$matches[4]}:{$matches[5]}:{$matches[6]}";
                } else {
                    $archiveDate = $fileDate;
                }

                $archives[] = [
                    'filename' => $fileName,
                    'path' => $file,
                    'size' => $this->formatBytes($fileSize),
                    'date' => $archiveDate,
                    'created_at' => $fileDate
                ];
            }

            // Sort by creation date, newest first
            usort($archives, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return view('admin.action-logs.archives', compact('archives'));
    }

    /**
     * Download an archived log file
     */
    public function downloadArchive($filename)
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized');
        }

        $filePath = storage_path('app/action_logs_archives/' . $filename);

        if (!file_exists($filePath) || !str_ends_with($filename, '.json')) {
            abort(404, 'Archive file not found');
        }

        return response()->download($filePath);
    }

    /**
     * Verify admin password for viewing action logs
     */
    public function verifyPassword(Request $request)
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $password = $request->input('password');

        // Verify the password against the authenticated user's password
        if (Hash::check($password, auth()->user()->password)) {
            return response()->json(['success' => true, 'message' => 'Password verified successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid password. Please try again.'], 401);
    }

    /**
     * Verify admin password for downloading archives
     */
    public function verifyArchivesPassword(Request $request)
    {
        // Ensure only admin can access
        if (auth()->user()->role !== 'ADMIN') {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $password = $request->input('password');

        // Verify the password against the authenticated user's password
        if (Hash::check($password, auth()->user()->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Password verified successfully',
                'downloadUrl' => $request->input('downloadUrl')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid password. Please try again.'], 401);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
