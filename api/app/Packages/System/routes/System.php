<?php


use Illuminate\Support\Facades\Route;
use App\Packages\System\Controllers\SystemController;
use App\Packages\System\Controllers\SystemCodeController;


Route::prefix('system')->group(function () {

    Route::get('', function () {
        return response()->json(['route' => 'allow']); 
    });


    
    // Rotas autenticadas via token jwt
    Route::group(['middleware' => 'auth.jwt'], function () {

        Route::get('auth', function () {
            return response()->json(['route' => 'auth']); 
        });

    });

    // Rotas autenticadas via token jwt.admin
    Route::group(['middleware' => 'auth.jwt.admin'], function () {

        // Route::get('newcode', [SystemCodeController::class , 'getNewCode']);

        Route::get('admin', function () {
            return response()->json(['route' => 'auth.admin']); 
        });
        
        Route::prefix('code')->group(function () {
            Route::post('', [SystemCodeController::class , 'store']);
            Route::delete('{code}', [SystemCodeController::class , 'destroy']);
            Route::get('/{code}', [SystemCodeController::class , 'show']);
            Route::get('', [SystemCodeController::class , 'index']);
        });


    });
    

});