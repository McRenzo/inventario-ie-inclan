@extends('layouts.logistica')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                Lote de inventario
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                {{ $lote->bien?->nombre ?? 'Bien sin nombre' }}
            </h1>

            <p class="mt-1 text-sm font-semibold text-slate-500">
                {{ $lote->codigo_interno }}
            </p>
        </div>

        <a
            href="{{ route('v2.bienes.index') }}"
            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
        >
            Volver al inventario
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Cantidad actual
            </p>

            <p class="mt-3 text-lg font-black text-slate-900">
                {{ number_format((float) $lote->cantidad_actual, 2) }}
                {{ $lote->unidad_medida }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Situación
            </p>

            <p class="mt-3 text-lg font-black capitalize text-slate-900">
                {{ str_replace('_', ' ', $lote->situacion) }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Ubicación
            </p>

            <p class="mt-3 text-lg font-black text-slate-900">
                {{ $lote->ubicacion?->nombre ?? 'Sin ubicación' }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Valor en libros
            </p>

            <p class="mt-3 text-lg font-black text-slate-900">
                S/ {{ number_format((float) ($lote->valor_actual ?? 0), 2) }}
            </p>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <h2 class="font-black text-slate-900">
                Información del lote
            </h2>

            <dl class="mt-5 divide-y divide-slate-100 text-sm">

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Código interno</dt>
                    <dd class="font-bold text-slate-900">
                        {{ $lote->codigo_interno }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Cantidad inicial</dt>
                    <dd class="font-bold text-slate-900">
                        {{ number_format((float) $lote->cantidad_inicial, 2) }}
                        {{ $lote->unidad_medida }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Cantidad actual</dt>
                    <dd class="font-bold text-slate-900">
                        {{ number_format((float) $lote->cantidad_actual, 2) }}
                        {{ $lote->unidad_medida }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Categoría</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->bien?->categoria?->nombre ?? 'Sin categoría' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Conservación</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->estadoConservacion?->nombre ?? 'No determinado' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Operatividad</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->estadoOperatividad?->nombre ?? 'No determinado' }}
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
                        {{ $lote->area?->nombre ?? 'Sin área' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Ubicación</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->ubicacion?->nombre ?? 'Sin ubicación' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Responsable</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->responsable_nombre ?: 'Sin responsable' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">DNI</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->responsable_dni ?: 'Sin registrar' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4 py-3">
                    <dt class="font-semibold text-slate-400">Cargo</dt>
                    <dd class="text-right font-bold text-slate-900">
                        {{ $lote->responsable_cargo ?: 'Sin registrar' }}
                    </dd>
                </div>

            </dl>
        </div>

    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
        <h2 class="font-black text-slate-900">
            Valorización
        </h2>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Valor unitario
                </p>
                <p class="mt-1 font-black text-slate-900">
                    S/ {{ number_format((float) ($lote->valor_unitario ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Valor total
                </p>
                <p class="mt-1 font-black text-slate-900">
                    S/ {{ number_format((float) ($lote->valor_total ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Fecha de ingreso
                </p>
                <p class="mt-1 font-black text-slate-900">
                    {{ $lote->fecha_ingreso?->format('d/m/Y') ?? 'Sin registrar' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-slate-400">
                    Proveedor
                </p>
                <p class="mt-1 font-black text-slate-900">
                    {{ $lote->proveedor ?: 'Sin registrar' }}
                </p>
            </div>

        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="font-black text-slate-900">
                Historial de movimientos
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Cantidad</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4">Responsable</th>
                        <th class="px-6 py-4">Usuario</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($lote->movimientos as $movimiento)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->fecha_movimiento?->format('d/m/Y H:i') }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold capitalize text-emerald-700">
                                    {{ str_replace('_', ' ', $movimiento->tipo) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $movimiento->cantidad !== null
                                    ? number_format((float) $movimiento->cantidad, 2)
                                    : '—' }}
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