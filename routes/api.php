<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;

use App\Models\Queve;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group( ['middleware' => ["auth:sanctum"]], function(){
    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('logout', [UserController::class, 'logout']);

    //rutas para Queve    
    Route::post("create-queve", [QueveController::class, "createQueve"]); // crear un blog
    Route::get("list-queve", [QueveController::class, "listQueve"]); //mostrar TODOS los blogs
    Route::get("show-queve/{id}", [QueveController::class, "showQueve"]); //mostrar UN solo blog
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
