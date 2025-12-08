<?php

namespace App;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function log(
        string $event,
        ?string $description = null,
        $subject = null,             // Model hoặc null
        array $properties = [],
        ?Request $request = null
    ): ActivityLog {
        $request ??= request();

        return ActivityLog::create([
            'user_id'      => Auth::id(),
            'event'        => $event,
            'description'  => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->getKey(),
            'properties'   => $properties ?: null,
            'ip'           => $request?->ip(),
            'user_agent'   => $request?->userAgent(),
        ]);
    }
}
