@extends('layouts.logistica')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Unidad individual
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                {{ $unidad->bien?->nombre ?? 'Bien sin nombre' }}
            </h1>

            <p class="mt-1 text-sm font-semibold text-slate-500">
                {{ $unidad->codigo_interno }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('v2.unidades.edit', $unidad) }}"
                class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Editar unidad
            </a>

            <a
                href="{{ route('v2.bienes.index') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
            >
                Volver al inventario
            </a>
        </div>

        @if (session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
@endif
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Situación
            </p>

            <p class="mt-3 text-lg font-black capitalize text-slate-900">
                {{ str_replace('_', ' ', $unidad->situacion) }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Conservación
            </p>

            <p class="mt-3 text-lg font-black text-slate-900">
                {{ $unidad->estadoConservacion?->nombre ?? 'No determinado' }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Ubicación
            </p>

            <p class="mt-3 text-lg font-black text-slate-900">
                {{ $unidad->ubicacion?->nombre ?? 'Sin ubicación' }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Valor en libros
            </p>

            <p class="mt-3 text-lg font-black text-slate-900">
                S/ {{ number_format((float) ($unidad->valor_actual ?? 0), 2) }}
            </p>
        </div>

    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                    Identificación QR
                </p>

                <h2 class="mt-2 text-lg font-black text-slate-900">
                    Código QR de la unidad
                </h2>

                <p class="mt-2 max-w-xl text-sm text-slate-500">
                    Al escanear este código se abrirá directamente la ficha de
                    {{ $unidad->codigo_interno }}.
                </p>

                <p class="mt-3 font-mono text-sm font-bold text-blue-600">
                    {{ $unidad->codigo_interno }}
                </p>
            </div>

            <div class="shrink-0 rounded-3xl border border-slate-200 bg-white p-4">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size(190)
                    ->margin(1)
                    ->generate(route('v2.unidades.show', $unidad)) !!}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <h2 class="font-black text-slate-900">
                Identificación
            </h2>

            <dl class="mt-5 divide-y divide-slate-100 text-sm">

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Código interno</dt>
                    <dd class="font-bold text-slate-900">
                        {{ $unidad->codigo_interno }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Código patrimonial</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->codigo_patrimonial ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Número de serie</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->numero_serie ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Marca</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->bien?->marca ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Modelo</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->bien?->modelo ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Categoría</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->bien?->categoria?->nombre ?? 'Sin categoría' }}
                    </dd>
                </div>

            </dl>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <h2 class="font-black text-slate-900">
                Asignación actual
            </h2>

            <dl class="mt-5 divide-y divide-slate-100 text-sm">

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Área</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->area?->nombre ?? 'Sin área' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Ubicación</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->ubicacion?->nombre ?? 'Sin ubicación' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Responsable</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->responsable_nombre ?: 'Sin responsable' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">DNI</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->responsable_dni ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Cargo</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->responsable_cargo ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Operatividad</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $unidad->estadoOperatividad?->nombre ?? 'No determinado' }}
                    </dd>
                </div>

            </dl>
        </div>

    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
        <h2 class="font-black text-slate-900">
            Valorización y adquisición
        </h2>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Valor de adquisición
                </p>
                <p class="mt-1 font-black text-slate-900">
                    S/ {{ number_format((float) ($unidad->valor_adquisicion ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Valor residual
                </p>
                <p class="mt-1 font-black text-slate-900">
                    S/ {{ number_format((float) ($unidad->valor_residual ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Fecha de ingreso
                </p>
                <p class="mt-1 font-black text-slate-900">
                    {{ $unidad->fecha_ingreso?->format('d/m/Y') ?? 'Sin registrar' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Proveedor
                </p>
                <p class="mt-1 font-black text-slate-900">
                    {{ $unidad->proveedor ?: 'Sin registrar' }}
                </p>
            </div>

        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="font-black text-slate-900">
                Historial de movimientos
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                Registro cronológico de cambios realizados sobre la unidad.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Área</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4">Responsable</th>
                        <th class="px-6 py-4">Usuario</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($unidad->movimientos as $movimiento)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->fecha_movimiento?->format('d/m/Y H:i') }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold capitalize text-blue-700">
                                    {{ str_replace('_', ' ', $movimiento->tipo) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->areaNueva?->nombre ?? 'Sin cambio' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->ubicacionNueva?->nombre ?? 'Sin cambio' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->responsable_nuevo_nombre ?: 'Sin responsable' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->usuario?->name ?? 'Sistema' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                No existen movimientos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection