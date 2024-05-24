<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemLogController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'level' => 'required|integer',
            'action' => 'required|string',
        ]);

        $log = SystemLog::create($data);

        return response()->json(['success' => 'Log criado com sucesso'], 201);
    }

    public static function logMessage($message, $action, $level = 1, $context = [])
    {
        SystemLog::create([
            'message' => $message,
            'level' => $level,
            'context' => $context,
            'action' => $action,
        ]);

        Log::$level($message, $context);
    }
}
