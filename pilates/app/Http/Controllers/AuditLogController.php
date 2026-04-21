<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (($request->user()->role ?? '') !== 'admin') {
                abort(403);
            }
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        $q = $request->query('q');
        $action = $request->query('action');
        $userId = $request->query('user_id');

        $query = AuditLog::with('user')->orderBy('id', 'desc');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('message', 'like', "%{$q}%")->orWhere('meta', 'like', "%{$q}%");
            });
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $logs = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('logs.index', compact('logs', 'users', 'q', 'action', 'userId'));
    }

    public function destroyBulk(Request $request)
    {
        $data = $request->validate([
            'mode' => 'required|in:all,today,last_month,except_current_month',
            'confirm_text' => 'required|string',
        ]);

        if (strtoupper($data['confirm_text']) !== 'KONFIRMASI') {
            $msg = 'Konfirmasi tidak cocok. Ketik KONFIRMASI untuk menghapus.';
            if ($request->expectsJson() || $request->ajax() || str_contains($request->header('X-Requested-With', ''), 'XMLHttpRequest')) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $mode = $data['mode'];

        if ($mode === 'all') {
            AuditLog::query()->delete();
        } elseif ($mode === 'today') {
            AuditLog::whereDate('created_at', Carbon::today())->delete();
        } elseif ($mode === 'last_month') {
            $dt = Carbon::now()->subMonth();
            AuditLog::whereMonth('created_at', $dt->month)->whereYear('created_at', $dt->year)->delete();
        } elseif ($mode === 'except_current_month') {
            $now = Carbon::now();
            AuditLog::where(function($q) use ($now) {
                $q->whereYear('created_at', '!=', $now->year)
                  ->orWhereMonth('created_at', '!=', $now->month);
            })->delete();
        }

        $message = 'Logs dihapus (mode: ' . $mode . ').';

        if ($request->expectsJson() || $request->ajax() || str_contains($request->header('X-Requested-With', ''), 'XMLHttpRequest')) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('logs.index')->with('success', $message);
    }
}
