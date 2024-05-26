<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class MainController extends Controller
{
    public function main(Request $request)
    {

        $serverStatus = true;
        $serverVersion = '1.05.1';
        $currentDateTime = Carbon::now();
        $formattedDate = $currentDateTime->format('Y-m-d');
        $formattedTime = $currentDateTime->format('H:i:s');

        // Informações do banco de dados

        $pdo = DB::connection()->getPdo();
        $driverVersion = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        $databaseName = env('DB_DATABASE');

        $sizeQuery = DB::select('
            SELECT table_schema AS `Database`,
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS `Size (MB)`
            FROM information_schema.TABLES
            WHERE table_schema = ?
            GROUP BY table_schema
        ', [$databaseName]);

        $size = $sizeQuery[0]->{'Size (MB)'};

        if ($serverStatus) {
            return response()->json([
                'status' => 'Server is on',
                'date' => $formattedDate,
                'time' => $formattedTime,
                'version' => $serverVersion,
                'driver_version' => $driverVersion,
                'database_size_mb' => $size
            ], 200);
        } else {
            return response()->json(['status' => 'Server is down'], 500);
        }
    }
}
