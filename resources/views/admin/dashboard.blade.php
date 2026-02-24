@extends('layouts.admin')

@section('content')
<div class="max-w-6xl">
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bienvenido, {{ Auth::user()->name }}</h2>
        <p class="text-slate-500 mt-1">Este es el estado actual de Palapa "La Casona".</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-10 -mt-10 opacity-50"></div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ocupación Hoy</p>
            <div class="flex items-end gap-2">
                <span class="text-4xl font-black text-slate-900">78%</span>
                <span class="text-sm font-bold text-emerald-600 mb-1">+2%</span>
            </div>
            <div class="mt-6 w-full bg-slate-100 rounded-full h-3 p-1">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-full rounded-full" style="width: 78%"></div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ingresos Mes</p>
            <span class="text-4xl font-black text-slate-900">$14,250</span>
            <p class="text-emerald-600 font-bold text-sm mt-4 italic">Cifras en MXN</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Eventos Próximos</p>
            <span class="text-4xl font-black text-slate-900">3</span>
            <p class="text-slate-400 font-medium text-sm mt-4 italic">Confirmados en Palapa</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/60 overflow-hidden">
        <div class="px-10 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-black text-slate-800 tracking-tight">Reservas Recientes</h3>
            <button class="px-5 py-2 bg-emerald-50 text-emerald-700 rounded-full text-xs font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition">Ver Todo</button>
        </div>
        <div class="p-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase tracking-[0.2em] font-black">
                        <th class="px-6 py-5">Cliente</th>
                        <th class="px-6 py-5">Servicio</th>
                        <th class="px-6 py-5">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="group hover:bg-emerald-50/30 transition">
                        <td class="px-6 py-6">
                            <div class="text-sm font-bold text-slate-800">Ruudvan Ordaz</div>
                            <div class="text-xs text-slate-400">ruud@example.com</div>
                        </td>
                        <td class="px-6 py-6 text-sm font-bold text-slate-600 italic">Habitación 04</td>
                        <td class="px-6 py-6 text-right md:text-left">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 border border-emerald-200 tracking-widest">
                                Confirmada
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection