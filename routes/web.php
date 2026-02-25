<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Autenticación ---

// 1. Ruta para VER el formulario de inicio de sesión (GET)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// 2. Ruta para PROCESAR las credenciales (POST)
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// 3. Ruta para Cerrar Sesión de forma segura
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- Rutas Protegidas (Requieren inicio de sesión previo) ---

Route::middleware('auth')->group(function () {
    
    // DASHBOARD PRINCIPAL (ACTUALIZADO)
    // Ahora conecta con el controlador para mostrar ingresos, ocupación y folios pendientes
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // --- Gestión de Habitaciones (CRUD de espacios en La Casona) ---
    Route::resource('admin/rooms', RoomController::class)->names('rooms');

    // --- Gestión de Reservaciones (CRUD y Calendario FullCalendar) ---
    
    // Listado principal con calendario y tabla de búsqueda por Folio
    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    
    // Verificación de disponibilidad de habitaciones
    Route::get('/admin/reservations/check', [ReservationController::class, 'checkAvailability'])->name('reservations.check');
    Route::post('/admin/reservations/verify', [ReservationController::class, 'verify'])->name('reservations.verify');
    
    // Almacenamiento de nuevas reservas (confirmación inicial)
    Route::post('/admin/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');

    // CONTROL MANUAL DE ESTADOS (Módulo de operación rápida)
    // Permite cambiar entre Confirmada, Pendiente o Cancelada directamente desde la tabla
    Route::patch('/admin/reservations/{reservation}/status/{status}', [ReservationController::class, 'updateStatus'])->name('reservations.status');

    // Gestión de registros existentes
    Route::get('/admin/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/admin/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/admin/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // DOCUMENTACIÓN LEGAL (Contrato de Hospedaje)
    // Generación de PDF con Base64 para evitar errores de la extensión GD
    Route::get('/admin/reservations/{reservation}/pdf', [ReservationController::class, 'downloadContract'])->name('reservations.pdf');

    // --- Próximamente: Gestión de Palapa / Eventos Sociales ---
    // Route::resource('admin/palapa', PalapaController::class)->names('palapa');
});