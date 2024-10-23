<?php

/**
 * @OA\Info(
 *     title="API de Wellezy",
 *     version="1.0.0",
 *     description="Documentación de la API para la autenticación de usuarios.",
 *     @OA\Contact(
 *         email="soporte@wellezy.com"
 *     )
 * ),
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor local"
 * )
 */

use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\FlightController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login'])/*->middleware('throttle:4,1')*/;
Route::middleware(['auth:sanctum'])->group(function () {
    //elimina el token
    Route::post('logout', [LogoutController::class, 'logout']);

    Route::post('airports', [AirportController::class, 'airports']);
    Route::post('flights', [FlightController::class, 'flights']);
});
