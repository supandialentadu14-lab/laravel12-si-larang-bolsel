<?php

use App\Http\Controllers\BtsApiController;
use Illuminate\Support\Facades\Route;

Route::get('/bts-towers', [BtsApiController::class, 'index']);
Route::get('/bts-towers/{id}', [BtsApiController::class, 'show']);
Route::get('/bts-towers/stats', [BtsApiController::class, 'stats']);
