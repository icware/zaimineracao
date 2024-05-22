<?php


use Illuminate\Support\Facades\Route;
use App\Packages\Company\Controllers\CompanyController;


Route::prefix('company')->group(function () {
  
    Route::group(['middleware' => 'auth.jwt'], function () {

        Route::get('auth', function () {
            return response()->json(['route' => 'auth']); 
        });

    }); 
    Route::group(['middleware' => 'auth.jwt.admin'], function () {
        Route::apiResource('', CompanyController::class );
    });
});