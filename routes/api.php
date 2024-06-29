<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['auth:sanctum'])->group(function () {//Routes protégées par une authentification
    Route::post('profils', [ProfilController::class, 'store'])->name('create');
    Route::delete('profils/{id}', [ProfilController::class, 'destroy'])->where('id', '[0-9]+')->name('delete');
    Route::put('profils/{id}', [ProfilController::class, 'update'])->where('id', '[0-9]+')->name('update');
});
Route::get('profils', [ProfilController::class, 'index'])->name('showProfils');

Route::post('/login', [AuthController::class, 'login'])->name('login');
