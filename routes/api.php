<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\EquipmentController;

Route::apiResource('site', SiteController::class);
Route::apiResource('user', UserController::class);
Route::apiResource('equipment', EquipmentController::class);