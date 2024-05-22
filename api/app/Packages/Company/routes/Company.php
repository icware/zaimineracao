<?php


use Illuminate\Support\Facades\Route;
use App\Packages\Company\Controllers\CompanyController;
use App\Packages\Company\Controllers\AssociateController;
use App\Packages\Company\Controllers\DisplayController;
use App\Packages\Company\Controllers\DisplaySourceController;
use App\Packages\Company\Models\Display;


    Route::apiResource('company', CompanyController::class);    
    Route::prefix('company')->group(function () {        
        Route::apiResource('/{company}/displays', DisplayController::class);
        Route::apiResource('/{company}/displays/{display}/sources', DisplaySourceController::class);
        Route::apiResource('/{company}/associates', AssociateController::class);
    });

    Route::group(['middleware' => 'auth.jwt'], function () {
 
    }); 
    Route::group(['middleware' => 'auth.jwt.admin'], function () {
    });
