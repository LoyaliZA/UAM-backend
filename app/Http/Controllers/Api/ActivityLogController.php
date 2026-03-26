<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActivityLogController extends Controller
{
    public function store(Request $request) {
    $v = Validator::make($request->all(), [
        'employee_identifier' => 'required|string',
        'event_type' => 'required|in:navigation,form_submit,clipboard,keystroke',
        'url_or_path' => 'nullable|string',
        'payload' => 'nullable|array',
    ]);

    if ($v->fails()) return response()->json($v->errors(), 422);

    ActivityLog::create($request->all() + ['ip_address' => $request->ip()]);
    return response()->json(['status' => 'success'], 201);
}
}