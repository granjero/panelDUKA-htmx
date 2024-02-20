<?php

use App\Http\Controllers\ControladorCliente;
use App\Http\Controllers\ControladorPosiciones;
use App\Http\Controllers\ControladorPosicionesResumen;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them wilf
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layout.layout');
});


Route::resource('/posiciones', ControladorPosiciones::class);
// Route::get('/posiciones/{cosecha}/{producto}/{cliente}', [ControladorPosiciones::class, 'consulta']);

Route::resource('/posiciones_resumen', ControladorPosicionesResumen::class);

Route::resource('/dolar', ControladorDolar::class);
