<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CreditConsulterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::group(['middleware' => ['auth']], function(){
    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);
    // Route::resource('blogs', BlogController::class);

    // Muestra vista de consultas de crédito
    Route::get('consulta', [CreditConsulterController::class, 'index']);

    Route::get('creditos', [CreditConsulterController::class, 'creditos']);
});


// -- Rutas de prueba para Frontend (sin usar API) --
Route::post('guardar-consulta', [CreditConsulterController::class, 'saveNewCreditConsulted']);
// Route::post('consultar-credito', [CreditConsulterController::class, 'getCreditNumberInfo']);
