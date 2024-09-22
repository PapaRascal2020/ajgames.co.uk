<?php

use App\Http\Controllers\PlayerController;

Route::post('register', [PlayerController::class, 'register']);
Route::post('login', [PlayerController::class, 'login']);