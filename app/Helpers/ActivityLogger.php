<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

trait ActivityLogger
{
    protected function logActivity(string $action, ?object $subject = null, ?string $description = null, ?array $properties = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'user_type' => Auth::guard('web')->check() ? 'App\Models\User' : null,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id ?? null,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => $properties,
        ]);
    }
}
