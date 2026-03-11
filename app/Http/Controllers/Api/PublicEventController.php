<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Mail\NewReservationAlert;
use App\Mail\GuestReservationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PublicEventController extends Controller
{
    public function getAvailability()
    {
        $occupiedDates = Event::where('status', '!=', 'cancelled')
            ->where('event_date', '>=', now()->toDateString())
            ->pluck('event_date');

        return response()->json($occupiedDates);
    }

    public function storePublicReservation(Request $request)
    {
        // 1. Validación (Agregamos email)
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email', // Indispensable para el envío
            'customer_phone' => 'required|string|max:20',
            'event_type'     => 'required|string',
            'event_date'     => 'required|date|after:today',
            'start_time'     => 'required',
            'end_time'       => 'required|after:start_time',
            'include_pool'   => 'boolean',
            'include_kitchen'=> 'boolean',
        ]);

        // 2. Verificar disponibilidad
        $isOccupied = Event::where('event_date', $validated['event_date'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($isOccupied) {
            return response()->json(['error' => 'La fecha ya no está disponible.'], 422);
        }

        // 3. Crear Reserva
        $event = Event::create(array_merge($validated, [
            'status' => 'pending',
            'total_price' => 0,
            'down_payment' => 0
        ]));

        // 4. ENVÍO DE CORREOS (Doble vía)
        try {
            // Correo 1: Al Encargado (puedes poner tu correo o el de Josue)
            Mail::to(config('mail.from.address'))->send(new NewReservationAlert($event));

            // Correo 2: Al Huésped (el email que viene del formulario de Astro)
            Mail::to($validated['customer_email'])->send(new GuestReservationConfirmation($event));

        } catch (\Exception $e) {
            Log::error("Error en envío de correos: " . $e->getMessage());
            // No bloqueamos la respuesta al usuario si el correo falla
        }

        return response()->json([
            'success' => true,
            'message' => '¡Solicitud enviada! Revisa tu correo para más detalles.',
            'folio'   => $event->folio
        ], 201);
    }
}