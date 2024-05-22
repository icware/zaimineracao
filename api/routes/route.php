<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\MainController;
use App\Http\Controllers\Main\AuthController;

Route::get('/', [MainController::class, 'main']);

Route::prefix('auth')->group(function () {
    Route::post('', [AuthController::class, 'store']);
    Route::post('token', [AuthController::class, 'get_token']);
    Route::get('token', [AuthController::class, 'check_token'])->middleware('auth.jwt');
    Route::get('', [AuthController::class, 'show'])->middleware('auth.jwt');
    Route::put('update', [AuthController::class, 'update'])->middleware('auth.jwt');
    Route::put('password/{auth_id}', [AuthController::class, 'update_password'])->middleware('auth.jwt');
    Route::post('password/{auth_id}', [AuthController::class, 'reset_password'])->middleware('auth.jwt');
});


if (class_exists('App\Packages\Company\routes\Company')) {
    // Importe as rotas do pacote Company
    include base_path('app/Packages/Company/routes/Company.php');
}

if (class_exists('App\Packages\System\routes\System')) {
    // Importe as rotas do pacote System
    include base_path('app/Packages/System/routes/System.php');
}