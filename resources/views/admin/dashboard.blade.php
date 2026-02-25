@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">
    {{-- Encabezado Dinámico --}}
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bienvenido, {{ Auth::user()->name }}</h2>
            <p class="text-slate-500 mt-1">Este es el estado actual de Palapa "La Casona".</p>
        </div>
        <div class="hidden md:block">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 bg-emerald-50 px-4 py-2 rounded-full border border-emerald-100">
                {{ now()->translatedFormat('d F, Y') }}
            </span>
        </div>
    </div>

    {{-- Grid de Métricas con Datos Reales --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-10 -mt-10 opacity-50 transition-transform group-hover:scale-110"></div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ocupación Hoy</p>
            <div class="flex items-end gap-2">
                @php
                    $totalHabitaciones = \App\Models\Room::count() ?: 1;
                    $porcentajeOcupacion = round(($ocupacionHoy / $totalHabitaciones) * 100);
                @endphp
                <span class="text-4xl font-black text-slate-900">{{ $porcentajeOcupacion }}%</span>
                <span class="text-sm font-bold text-emerald-600 mb-1">Activa</span>
            </div>
            <div class="mt-6 w-full bg-slate-100 rounded-full h-3 p-1">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-full rounded-full transition-all duration-1000" style="width: {{ $porcentajeOcupacion }}%"></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-3 font-bold uppercase">{{ $ocupacionHoy }} de {{ $totalHabitaciones }} espacios ocupados</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 relative">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ingresos Mes</p>
            <span class="text-4xl font-black text-slate-900">${{ number_format($ingresosMes, 2) }}</span>
            <div class="mt-4 flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <p class="text-emerald-600 font-bold text-[10px] uppercase tracking-wider">Cifras en MXN</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pendientes de Pago</p>
            <span class="text-4xl font-black text-amber-500">{{ $pendientesPago }}</span>
            <p class="text-slate-400 font-medium text-sm mt-4 italic">Folios por confirmar</p>
        </div>
    </div>

    {{-- SECCIÓN DE GRÁFICAS (NUEVO) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-xl shadow-slate-200/50">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-sm">Comportamiento de Ingresos</h3>
                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-wider">Últimos 6 meses</span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-emerald-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-xl shadow-emerald-900/40">
            <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500 rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
            
            <div>
                <h3 class="text-2xl font-black mb-2 leading-tight">Gestión de<br>la Palapa</h3>
                <p class="text-emerald-200 text-sm font-medium mt-2">Administra los eventos sociales, bodas y fiestas en el área común.</p>
            </div>

            <a href="#" class="inline-flex items-center justify-center w-full py-4 bg-white text-emerald-900 font-black rounded-2xl hover:bg-emerald-50 transition transform hover:-translate-y-1 mt-6 text-sm uppercase tracking-widest shadow-lg">
                Ir a Eventos
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Tabla de Reservas Recientes --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/60 overflow-hidden">
        <div class="px-10 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-black text-slate-800 tracking-tight uppercase text-sm">Actividad Reciente</h3>
            <a href="{{ route('reservations.index') }}" class="px-5 py-2 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition shadow-sm">Ver Todo</a>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-slate-50">
                        <th class="px-6 py-5">Folio / Cliente</th>
                        <th class="px-6 py-5 text-center">Espacio</th>
                        <th class="px-6 py-5 text-center">Check-in</th>
                        <th class="px-6 py-5 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recientes as $rec)
                    <tr class="group hover:bg-slate-50/50 transition">
                        <td class="px-6 py-6">
                            <div class="text-[10px] font-black text-emerald-600 uppercase mb-0.5">{{ $rec->folio }}</div>
                            <div class="text-sm font-bold text-slate-800">{{ $rec->customer_name }}</div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-flex px-3 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-black uppercase">
                                {{ $rec->room->name }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center text-sm font-bold text-slate-600">
                            {{ \Carbon\Carbon::parse($rec->check_in)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-6 text-right font-black text-slate-900">
                            ${{ number_format($rec->total_price, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400 font-bold uppercase text-xs tracking-widest">No hay actividad reciente</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT DE LA GRÁFICA --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradiente vertical para la gráfica
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // Inicio (Verde)
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)'); // Fin (Transparente)

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Ingresos ($)',
                    data: @json($data),
                    borderColor: '#059669', // Emerald-600
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#059669',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    fill: true,
                    tension: 0.4 // Curva suave (Spline)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, // Ocultamos la leyenda por limpieza
                    tooltip: {
                        backgroundColor: '#064e3b', // Emerald-900
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + new Intl.NumberFormat('es-MX').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 10, weight: 'bold' },
                            color: '#94a3b8',
                            callback: function(value) { return '$' + value; }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: 'bold' },
                            color: '#64748b'
                        },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection