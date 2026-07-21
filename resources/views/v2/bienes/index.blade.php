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
                Inventario
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

            <a
                href="{{ route(
                    'v2.bienes.exportar',
                    request()->query()
                ) }}"
                class="inline-flex items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100"
            >
                Exportar Excel
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
            {{ session('error') }}
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
            class="space-y-4"
        >
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                <div class="xl:col-span-2">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Buscar
                    </label>

                    <div class="relative">
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
                            placeholder="Código, serie, nombre, marca, modelo o responsable"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Tipo de registro
                    </label>

                    <select
                        name="tipo"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option
                            value="todos"
                            @selected($filtros['tipo'] === 'todos')
                        >
                            Todos
                        </option>

                        <option
                            value="unidades"
                            @selected($filtros['tipo'] === 'unidades')
                        >
                            Unidades individuales
                        </option>

                        <option
                            value="lotes"
                            @selected($filtros['tipo'] === 'lotes')
                        >
                            Lotes
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Categoría
                    </label>

                    <select
                        name="categoria_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Todas las categorías</option>

                        @foreach ($categorias as $categoria)
                            <option
                                value="{{ $categoria->id }}"
                                @selected(
                                    $filtros['categoria_id'] === $categoria->id
                                )
                            >
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Área
                    </label>

                    <select
                        name="area_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Todas las áreas</option>

                        @foreach ($areas as $area)
                            <option
                                value="{{ $area->id }}"
                                @selected(
                                    $filtros['area_id'] === $area->id
                                )
                            >
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Ubicación
                    </label>

                    <select
                        name="ubicacion_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Todas las ubicaciones</option>

                        @foreach ($ubicaciones as $ubicacion)
                            <option
                                value="{{ $ubicacion->id }}"
                                @selected(
                                    $filtros['ubicacion_id'] === $ubicacion->id
                                )
                            >
                                {{ $ubicacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Conservación
                    </label>

                    <select
                        name="estado_conservacion_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Todos los estados</option>

                        @foreach ($estadosConservacion as $estado)
                            <option
                                value="{{ $estado->id }}"
                                @selected(
                                    $filtros['estado_conservacion_id']
                                    === $estado->id
                                )
                            >
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                        Situación
                    </label>

                    <select
                        name="situacion"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Todas las situaciones</option>

                        <option
                            value="disponible"
                            @selected($filtros['situacion'] === 'disponible')
                        >
                            Disponible
                        </option>

                        <option
                            value="asignado"
                            @selected($filtros['situacion'] === 'asignado')
                        >
                            Asignado
                        </option>

                        <option
                            value="prestado"
                            @selected($filtros['situacion'] === 'prestado')
                        >
                            Prestado
                        </option>

                        <option
                            value="en_mantenimiento"
                            @selected(
                                $filtros['situacion'] === 'en_mantenimiento'
                            )
                        >
                            En mantenimiento
                        </option>

                        <option
                            value="no_encontrado"
                            @selected(
                                $filtros['situacion'] === 'no_encontrado'
                            )
                        >
                            No encontrado
                        </option>

                        <option
                            value="en_proceso_de_baja"
                            @selected(
                                $filtros['situacion'] === 'en_proceso_de_baja'
                            )
                        >
                            En proceso de baja
                        </option>

                        <option
                            value="dado_de_baja"
                            @selected(
                                $filtros['situacion'] === 'dado_de_baja'
                            )
                        >
                            Dado de baja
                        </option>
                    </select>
                </div>

            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-xs font-semibold text-slate-400">
                    Los filtros se aplican tanto a unidades como a lotes.
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('v2.bienes.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
                    >
                        Limpiar filtros
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                    >
                        Aplicar filtros
                    </button>
                </div>
            </div>
        </form>
    </div>

    <form
        method="POST"
        action="{{ route('v2.etiquetas.imprimir') }}"
        id="formEtiquetas"
        class="space-y-6"
    >
        @csrf

        <div class="flex flex-col gap-3 rounded-3xl border border-slate-200/70 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-black text-slate-900">
                    Impresión de etiquetas QR
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Selecciona unidades o lotes y genera una hoja lista para imprimir.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span
                    id="contadorSeleccionados"
                    class="text-xs font-bold text-slate-500"
                >
                    0 seleccionados
                </span>

                <button
                    type="submit"
                    id="btnImprimirEtiquetas"
                    disabled
                    class="inline-flex cursor-not-allowed items-center justify-center rounded-2xl bg-slate-300 px-5 py-3 text-sm font-bold text-white transition"
                >
                    Imprimir etiquetas QR
                </button>
            </div>
        </div>

        {{-- Tabla de unidades --}}
        @if ($filtros['tipo'] !== 'lotes')
            <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="font-black text-slate-900">
                            Bienes individuales
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $unidades->total() }} resultado(s) encontrado(s).
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                                <th class="w-12 px-5 py-4">
                                    <input
                                        type="checkbox"
                                        id="seleccionarTodasUnidades"
                                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        title="Seleccionar todas las unidades visibles"
                                    >
                                </th>
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

                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="checkbox"
                                                name="unidades[]"
                                                value="{{ $unidad->id }}"
                                                class="selector-etiqueta selector-unidad h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                                aria-label="Seleccionar {{ $unidad->codigo_interno }}"
                                            >

                                            <input
                                                type="number"
                                                name="copias_unidades[{{ $unidad->id }}]"
                                                value="1"
                                                min="1"
                                                max="20"
                                                class="w-16 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs"
                                                title="Cantidad de copias"
                                            >
                                        </div>
                                    </td>
                                    
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

                                        <a
                                            href="{{ route('v2.bienes.edit', $unidad->bien) }}"
                                            class="mt-2 inline-flex text-xs font-bold text-blue-600 hover:text-blue-800"
                                        >
                                            Editar ficha
                                        </a>
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
                                    <td colspan="9" class="px-5 py-14 text-center">
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
        @endif

        {{-- Tabla de lotes --}}
        @if ($filtros['tipo'] !== 'unidades')
            <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="font-black text-slate-900">
                            Lotes
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $lotes->total() }} resultado(s) encontrado(s).
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                                <th class="w-12 px-5 py-4">
                                    <input
                                        type="checkbox"
                                        id="seleccionarTodosLotes"
                                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                        title="Seleccionar todos los lotes visibles"
                                    >
                                </th>
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

                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="checkbox"
                                                name="lotes[]"
                                                value="{{ $lote->id }}"
                                                class="selector-etiqueta selector-lote h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                                aria-label="Seleccionar {{ $lote->codigo_interno }}"
                                            >

                                            <input
                                                type="number"
                                                name="copias_lotes[{{ $lote->id }}]"
                                                value="1"
                                                min="1"
                                                max="20"
                                                class="w-16 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs"
                                                title="Cantidad de copias"
                                            >
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="font-bold text-blue-600">
                                            {{ $lote->codigo_interno }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <p class="font-bold text-slate-900">
                                            {{ $lote->bien?->nombre ?? 'Sin ficha' }}
                                        </p>
                                        <a
                                            href="{{ route('v2.bienes.edit', $lote->bien) }}"
                                            class="mt-2 inline-flex text-xs font-bold text-blue-600 hover:text-blue-800"
                                        >
                                            Editar ficha
                                        </a>
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
                                            href="{{ route('v2.lotes.show', $lote) }}"
                                            class="inline-flex rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700"
                                        >
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-14 text-center">
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
        @endif
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectores = Array.from(
            document.querySelectorAll('.selector-etiqueta')
        );

        const selectoresUnidades = Array.from(
            document.querySelectorAll('.selector-unidad')
        );

        const selectoresLotes = Array.from(
            document.querySelectorAll('.selector-lote')
        );

        const seleccionarTodasUnidades = document.getElementById(
            'seleccionarTodasUnidades'
        );

        const seleccionarTodosLotes = document.getElementById(
            'seleccionarTodosLotes'
        );

        const contador = document.getElementById(
            'contadorSeleccionados'
        );

        const boton = document.getElementById(
            'btnImprimirEtiquetas'
        );

        const camposCopias = Array.from(
            document.querySelectorAll(
                'input[name^="copias_unidades"], input[name^="copias_lotes"]'
            )
        );

        function obtenerCopias(selector) {
            const fila = selector.closest('td');
            const campoCopias = fila?.querySelector(
                'input[type="number"]'
            );

            if (!campoCopias) {
                return 1;
            }

            let cantidad = Number.parseInt(
                campoCopias.value,
                10
            );

            if (Number.isNaN(cantidad)) {
                cantidad = 1;
            }

            cantidad = Math.max(1, Math.min(cantidad, 20));
            campoCopias.value = cantidad;

            return cantidad;
        }

        function actualizarEstado() {
            const seleccionados = selectores.filter(
                selector => selector.checked
            );

            const cantidadActivos = seleccionados.length;

            const cantidadEtiquetas = seleccionados.reduce(
                function (total, selector) {
                    return total + obtenerCopias(selector);
                },
                0
            );

            const textoActivos =
                cantidadActivos === 1
                    ? '1 activo seleccionado'
                    : `${cantidadActivos} activos seleccionados`;

            const textoEtiquetas =
                cantidadEtiquetas === 1
                    ? '1 etiqueta'
                    : `${cantidadEtiquetas} etiquetas`;

            contador.textContent =
                `${textoActivos} · ${textoEtiquetas}`;

            boton.disabled = cantidadActivos === 0;

            boton.className = cantidadActivos > 0
                ? 'inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700'
                : 'inline-flex cursor-not-allowed items-center justify-center rounded-2xl bg-slate-300 px-5 py-3 text-sm font-bold text-white transition';

            if (seleccionarTodasUnidades) {
                const marcadas = selectoresUnidades.filter(
                    selector => selector.checked
                ).length;

                seleccionarTodasUnidades.checked =
                    selectoresUnidades.length > 0
                    && marcadas === selectoresUnidades.length;

                seleccionarTodasUnidades.indeterminate =
                    marcadas > 0
                    && marcadas < selectoresUnidades.length;
            }

            if (seleccionarTodosLotes) {
                const marcados = selectoresLotes.filter(
                    selector => selector.checked
                ).length;

                seleccionarTodosLotes.checked =
                    selectoresLotes.length > 0
                    && marcados === selectoresLotes.length;

                seleccionarTodosLotes.indeterminate =
                    marcados > 0
                    && marcados < selectoresLotes.length;
            }
        }

        selectores.forEach(function (selector) {
            selector.addEventListener(
                'change',
                actualizarEstado
            );
        });

        camposCopias.forEach(function (campo) {
            campo.addEventListener(
                'input',
                actualizarEstado
            );

            campo.addEventListener(
                'change',
                actualizarEstado
            );
        });

        if (seleccionarTodasUnidades) {
            seleccionarTodasUnidades.addEventListener(
                'change',
                function () {
                    selectoresUnidades.forEach(function (selector) {
                        selector.checked =
                            seleccionarTodasUnidades.checked;
                    });

                    actualizarEstado();
                }
            );
        }

        if (seleccionarTodosLotes) {
            seleccionarTodosLotes.addEventListener(
                'change',
                function () {
                    selectoresLotes.forEach(function (selector) {
                        selector.checked =
                            seleccionarTodosLotes.checked;
                    });

                    actualizarEstado();
                }
            );
        }

        actualizarEstado();
    });
</script>

@endsection