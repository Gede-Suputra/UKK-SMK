<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    // Define actions as constants for easy reuse / filtering
    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';
    public const ACTION_LOGIN = 'login';
    public const ACTION_LOGOUT = 'logout';

    /**
     * Write a log entry.
     * @param string $action
     * @param array $attributes keys: user_id (optional), subject_type, subject_id, message, meta
     */
    public static function log(string $action, array $attributes = []) : AuditLog
    {
        $userId = $attributes['user_id'] ?? (Auth::check() ? Auth::id() : null);

        // Prevent near-duplicate logs: if an identical log (same user/action/subject/message)
        // was created within the last N seconds, skip creating another one.
        $dedupWindowSeconds = 2;
        $subjectType = $attributes['subject_type'] ?? null;
        $subjectId = $attributes['subject_id'] ?? null;
        $message = isset($attributes['message']) ? (string) $attributes['message'] : null;

        $recent = AuditLog::where('user_id', $userId)
            ->where('action', $action)
            ->where('subject_type', $subjectType)
            ->where('subject_id', $subjectId)
            ->where('message', $message)
            ->where('created_at', '>=', now()->subSeconds($dedupWindowSeconds))
            ->exists();

        if ($recent) {
            // return last matching entry for convenience
            return AuditLog::where('user_id', $userId)
                ->where('action', $action)
                ->where('subject_type', $subjectType)
                ->where('subject_id', $subjectId)
                ->where('message', $message)
                ->orderBy('id', 'desc')
                ->first();
        }

        $entry = AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'message' => $message,
            'meta' => $attributes['meta'] ?? null,
        ]);

        return $entry;
    }
}
