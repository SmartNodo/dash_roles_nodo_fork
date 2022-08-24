<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QueveController;
use App\Http\Controllers\Api\CreditConsulterController;
use App\Http\Controllers\Api\AccessKeyController;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group( ['middleware' => ["auth:sanctum"]], function(){
    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('logout', [UserController::class, 'logout']);
    Route::post('user-update', [UserController::class, 'userUpdate']);

    //rutas para Queve
    Route::post('create-queve', [QueveController::class, 'createQueve']);
    Route::get("list-queve", [QueveController::class, "listQueve"]);
    Route::get("show-queve/{id}", [QueveController::class, "showQueve"]);

    Route::put("update-queve/{id}", [QueveController::class, "updateQueve"]);
    Route::delete("delete-queve/{id}", [QueveController::class, "deleteQueve"]);

    // Ruta para guardar crédito en la BD
    Route::post('check-credit-number', [CreditConsulterController::class, 'checkCreditNumber']);
    Route::get("list-credits", [CreditConsulterController::class, "listCredits"]);

    // CRUD llaves de acceso
    Route::get('access-keys', [AccessKeyController::class, 'index'])->name('access-keys');
    Route::post('access-keys/{accessKey}/update', [AccessKeyController::class, 'update'])->name('access-keys.update');

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
