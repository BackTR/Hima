<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    public static function log(string $action, string $description, string $model = null, string $model_id = null): void
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $model_id,
            'description' => $description,
            'ip_address'  => Request::ip(),
        ]);
    }
}