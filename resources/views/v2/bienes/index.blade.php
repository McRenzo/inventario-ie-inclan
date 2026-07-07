@extends('layouts.logistica')

@section('content')
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Inventario institucional
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Inventario V2
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Bienes individuales y lotes registrados en la nueva estructura.
            </p>
            
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('v2.bienes.create') }}"
                class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Nueva ficha de bien
            </a>

            <a
                href="{{ route('v2.unidades.create') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-blue-200 bg-blue-50 px-5 py-3 text-sm font-bold text-blue-700 transition hover:bg-blue-100"
            >
                Registrar unidad
            </a>

            <a
                href="{{ route('v2.lotes.create') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100"
            >
                Registrar lote
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Fichas de bienes
            </p>

            <p class="mt-3 text-3xl font-black text-slate-900">
                {{ number_format($resumen['fichas']) }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Unidades individuales
            </p>

            <p class="mt-3 text-3xl font-black text-slate-900">
                {{ number_format($resumen['unidades']) }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Lotes registrados
            </p>

            <p class="mt-3 text-3xl font-black text-slate-900">
                {{ number_format($resumen['lotes']) }}
            </p>

            <p class="mt-1 text-xs font-medium text-slate-400">
                {{ number_format($resumen['cantidad_lotes'], 2) }}
                unidades agrupadas
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Valor en libros
            </p>

            <p class="mt-3 text-3xl font-black text-slate-900">
                S/ {{ number_format($resumen['valor_total'], 2) }}
            </p>
        </div>

    </div>

    {{-- Buscador --}}
    <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
        <form
            method="GET"
            action="{{ route('v2.bienes.index') }}"
            class="flex flex-col gap-3 sm:flex-row"
        >
            <div class="relative flex-1">
                <svg
                    class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                    />
                </svg>

                <input
                    type="search"
                    name="buscar"
                    value="{{ $busqueda }}"
                    placeholder="Buscar por código, serie, descripción, marca o responsable"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                >
            </div>

            <button
                type="submit"
                class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Buscar
            </button>
        </form>

        @if ($busqueda !== '')
            <div class="mt-3">
                <a
                    href="{{ route('v2.bienes.index') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                >
                    Limpiar búsqueda
                </a>
            </div>
        @endif
    </div>

    {{-- Tabla de unidades --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-black text-slate-900">
                    Bienes individuales
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Equipos o bienes controlados uno por uno.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-4">Código</th>
                        <th class="px-5 py-4">Bien</th>
                        <th class="px-5 py-4">Serie</th>
                        <th class="px-5 py-4">Ubicación</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Situación</th>
                        <th class="px-5 py-4 text-right">Valor</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($unidades as $unidad)
                        <tr class="transition hover:bg-slate-50/70">

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-bold text-blue-600">
                                    {{ $unidad->codigo_interno }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">
                                    {{ $unidad->bien?->nombre ?? 'Sin ficha' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ trim(
                                        ($unidad->bien?->marca ?? '') . ' ' .
                                        ($unidad->bien?->modelo ?? '')
                                    ) ?: 'Sin marca o modelo' }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $unidad->numero_serie ?: 'Sin serie' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $unidad->ubicacion?->nombre ?? 'Sin ubicación' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                    {{ $unidad->estadoConservacion?->nombre ?? 'No determinado' }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold capitalize text-blue-700">
                                    {{ str_replace('_', ' ', $unidad->situacion) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right font-bold text-slate-900">
                                S/ {{ number_format((float) ($unidad->valor_actual ?? 0), 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a
                                    href="{{ route('v2.unidades.show', $unidad) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-700"
                                >
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <p class="font-semibold text-slate-500">
                                    No se encontraron bienes individuales.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($unidades->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $unidades->links() }}
            </div>
        @endif
    </div>

    {{-- Tabla de lotes --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-black text-slate-900">
                    Lotes
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Bienes o materiales gestionados por cantidad.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-4">Código</th>
                        <th class="px-5 py-4">Bien</th>
                        <th class="px-5 py-4">Cantidad</th>
                        <th class="px-5 py-4">Ubicación</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Situación</th>
                        <th class="px-5 py-4 text-right">Valor</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($lotes as $lote)
                        <tr class="transition hover:bg-slate-50/70">

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-bold text-blue-600">
                                    {{ $lote->codigo_interno }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">
                                    {{ $lote->bien?->nombre ?? 'Sin ficha' }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ number_format($lote->cantidad_actual, 2) }}
                                {{ $lote->unidad_medida }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $lote->ubicacion?->nombre ?? 'Sin ubicación' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                    {{ $lote->estadoConservacion?->nombre ?? 'No determinado' }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold capitalize text-blue-700">
                                    {{ str_replace('_', ' ', $lote->situacion) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right font-bold text-slate-900">
                                S/ {{ number_format((float) ($lote->valor_actual ?? 0), 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a
                                    href="{{ route('v2.unidades.show', $unidad) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-700"
                                >
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <p class="font-semibold text-slate-500">
                                    No se encontraron lotes.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($lotes->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $lotes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection