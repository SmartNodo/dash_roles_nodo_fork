<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QueveController;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group( ['middleware' => ["auth:sanctum"]], function(){
    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('logout', [UserController::class, 'logout']);

    //rutas para Queve    
    Route::post('create-queve', [QueveController::class, 'createQueve']); 
    Route::get("list-queve", [QueveController::class, "listQueve"]); 
    Route::get("show-queve/{id}", [QueveController::class, "showQueve"]);

    Route::put("update-queve/{id}", [QueveController::class, "updateQueve"]);
    Route::delete("delete-queve/{id}", [QueveController::class, "deleteQueve"]);
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
