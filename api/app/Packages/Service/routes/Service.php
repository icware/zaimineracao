<?php


use Illuminate\Support\Facades\Route;
use App\Packages\Service\Controllers\ServiceController;


Route::apiResource("services", ServiceController::class);
