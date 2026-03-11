<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicEventController;

// Prefijo automático: /api/
Route::get('/availability', [PublicEventController::class, 'getAvailability']);
Route::post('/reserve', [PublicEventController::class, 'storePublicReservation']);