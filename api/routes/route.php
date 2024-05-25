<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\AuthController;
use App\Http\Controllers\Main\MainController;
use App\Http\Controllers\Main\EmailVerificationController;
use App\Http\Controllers\Main\ManageUserController;

Route::get('/', [MainController::class, 'main'])->middleware('auth.jwt');

Route::post('/email/verify', [EmailVerificationController::class, 'get_email_verify'])->middleware('auth.jwt');
Route::post('/email/verify/code', [EmailVerificationController::class, 'email_verify'])->middleware('auth.jwt');


Route::prefix('auth')->group(function () {
    Route::get('', [AuthController::class, 'get_auth'])->middleware('auth.jwt');
    Route::post('', [ManageUserController::class, 'store']);
    Route::put('', [ManageUserController::class, 'update'])->middleware('auth.jwt');

    Route::post('login', [AuthController::class, 'login']);
    Route::get('token', [AuthController::class, 'token'])->middleware(['auth.jwt']);
    Route::get('admin/token', [AuthController::class, 'token'])->middleware(['auth.jwt.admin']);

    Route::prefix('manage')->group(function () {
        Route::get('theme', [ManageUserController::class, 'storeConfigTheme'])->middleware('auth.jwt');

        Route::prefix('password')->group(function () {
            Route::put('update', [ManageUserController::class, 'update_password'])->middleware('auth.jwt');
            Route::post('reset', [ManageUserController::class, 'reset_password']);
            Route::post('new', [ManageUserController::class, 'new_password']);
        });
    });
});


if (class_exists('App\Packages\Company\routes\Company')) {
    // Importe as rotas do pacote Company
    include base_path('app/Packages/Company/routes/Company.php');
}

if (class_exists('App\Packages\System\routes\System')) {
    // Importe as rotas do pacote System
    include base_path('app/Packages/System/routes/System.php');
}

if (class_exists('App\Packages\Service\routes\Service')) {
    // Importe as rotas do pacote System
    include base_path('app/Packages/Service/routes/Service.php');
}
