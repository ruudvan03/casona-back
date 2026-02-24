<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Autenticación ---

// 1. Ruta para VER el formulario (GET)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// 2. Ruta para PROCESAR el formulario (POST)
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// 3. Ruta para Cerrar Sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- Rutas Protegidas (Solo usuarios logueados) ---

Route::middleware('auth')->group(function () {
    
    // Dashboard Principal
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // --- Gestión de Habitaciones ---
    // Usamos resource para habilitar automáticamente:
    // index, create, store, show, edit, update y destroy
    Route::resource('admin/rooms', RoomController::class)->names('rooms');

    // --- Próximamente: Gestión de Palapa / Eventos ---
    // Route::resource('admin/palapa', PalapaController::class)->names('palapa');
});