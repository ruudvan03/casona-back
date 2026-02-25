<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->format('Y-m-d');
        $mesActual = now()->month;

        // 1. Ingresos del mes actual (Solo reservas confirmadas)
        $ingresosMes = Reservation::where('status', 'confirmed')
            ->whereMonth('check_in', $mesActual)
            ->sum('total_price');

        // 2. Ocupación Hoy (Habitaciones activas)
        $ocupacionHoy = Reservation::where('status', 'confirmed')
            ->where('check_in', '<=', $hoy)
            ->where('check_out', '>=', $hoy)
            ->count();

        // 3. Pendientes de Pago / Confirmación
        $pendientesPago = Reservation::where('status', 'pending')->count();

        // 4. Próximas Llegadas (En los siguientes 7 días)
        $proximasLlegadas = Reservation::where('status', 'confirmed')
            ->whereBetween('check_in', [now(), now()->addDays(7)])
            ->count();

        // 5. Últimas 5 reservaciones para la tabla de actividad
        $recientes = Reservation::with('room')->latest()->take(5)->get();

        // 6. DATOS PARA LA GRÁFICA (Ingresos últimos 6 meses)
        $ingresosPorMes = Reservation::where('status', 'confirmed')
            ->selectRaw('SUM(total_price) as total, DATE_FORMAT(check_in, "%Y-%m") as mes')
            ->where('check_in', '>=', now()->subMonths(6)) // Filtro de tiempo
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // Inicializamos arrays para Chart.js
        $labels = [];
        $data = [];

        // Procesamos los datos para separar etiquetas y valores
        foreach ($ingresosPorMes as $registro) {
            // Formateamos la fecha (Ej: "Febrero 2026")
            $labels[] = Carbon::parse($registro->mes)->translatedFormat('F Y');
            $data[] = $registro->total;
        }

        return view('admin.dashboard', compact(
            'ingresosMes', 
            'ocupacionHoy', 
            'pendientesPago', 
            'proximasLlegadas', 
            'recientes',
            'labels', // Etiquetas para el eje X de la gráfica
            'data'    // Valores para el eje Y de la gráfica
        ));
    }
}