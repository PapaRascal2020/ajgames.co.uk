<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlayerController;

Route::post('login', [PlayerController::class, 'login']);
Route::post('add-friend', [PlayerController::class, 'addFriend']);
Route::get('online-status/{playerId}', [PlayerController::class, 'getOnlineStatus']);
Route::get('photon-room/{playerId}', [PlayerController::class, 'getPhotonRoom']);
Route::post('update-status-room', [PlayerController::class, 'updateStatusAndRoom']);
Route::post('register', [PlayerController::class, 'register']);
Route::get('/player/{playerId}/friends', [PlayerController::class, 'listFriends']);
