<?php


use Illuminate\Support\Facades\Route;
use App\Packages\Summary\Controllers\SummaryController;


Route::prefix('summary')->group(function () {

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

        Route::get('admin', function () {
            return response()->json(['route' => 'auth.admin']); 
        });

    });
    

});