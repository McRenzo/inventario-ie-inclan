@extends('layouts.logistica')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Lotes
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    Fusionar lotes
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Une otro lote compatible al lote principal.
                </p>
            </div>

            <a
                href="{{ route('v2.lotes.show', $lote) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
            >
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-700">
                    No se pudo realizar la fusión
                </p>

                <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                Lote principal
            </p>

            <div class="mt-4 grid gap-4 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500">
                        Código
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        {{ $lote->codigo_interno }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500">
                        Bien
                    </p>

                    <p class="mt-1 font-semibold text-slate-900">
                        {{ $lote->bien->nombre ?? 'Sin nombre' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500">
                        Cantidad actual
                    </p>

                    <p class="mt-1 font-semibold text-slate-900">
                        {{ number_format((float) $lote->cantidad_actual, 2) }}
                        {{ $lote->unidad_medida }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500">
                        Ubicación
                    </p>

                    <p class="mt-1 font-semibold text-slate-900">
                        {{ $lote->ubicacion->nombre ?? 'Sin ubicación' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                Seleccionar lotes secundarios
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Solo aparecen lotes con las mismas características del lote principal.
            </p>

            @if ($lotesCompatibles->isEmpty())
                <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <p class="font-semibold text-amber-800">
                        No existen lotes compatibles para fusionar.
                    </p>

                    <p class="mt-1 text-sm text-amber-700">
                        Deben coincidir el bien, unidad de medida, área,
                        ubicación, responsable, situación, estados y valor unitario.
                    </p>
                </div>
            @else
                <form
                    method="POST"
                    action="{{ route('v2.lotes.fusion.store', $lote) }}"
                    class="mt-6 space-y-6"
                >
                    @csrf

                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($lotesCompatibles as $compatible)
                            <label
                                class="cursor-pointer rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50/40"
                            >
                                <div class="flex items-start gap-3">
                                    <input
                                        type="checkbox"
                                        name="lotes_secundarios[]"
                                        value="{{ $compatible->id }}"
                                        @checked(
                                            in_array(
                                                $compatible->id,
                                                old('lotes_secundarios', [])
                                            )
                                        )
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    >

                                    <div class="flex-1">
                                        <p class="font-bold text-slate-900">
                                            {{ $compatible->codigo_interno }}
                                        </p>

                                        <div class="mt-3 grid gap-2 text-sm text-slate-600 sm:grid-cols-2">
                                            <p>
                                                <span class="font-semibold">
                                                    Cantidad:
                                                </span>

                                                {{ number_format(
                                                    (float) $compatible->cantidad_actual,
                                                    2
                                                ) }}
                                                {{ $compatible->unidad_medida }}
                                            </p>

                                            <p>
                                                <span class="font-semibold">
                                                    Ubicación:
                                                </span>

                                                {{ $compatible->ubicacion->nombre
                                                    ?? 'Sin ubicación' }}
                                            </p>

                                            <p>
                                                <span class="font-semibold">
                                                    Situación:
                                                </span>

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $compatible->situacion
                                                    )
                                                ) }}
                                            </p>

                                            <p>
                                                <span class="font-semibold">
                                                    Responsable:
                                                </span>

                                                {{ $compatible->responsable_nombre
                                                    ?: 'Sin responsable' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label
                                for="fecha_fusion"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Fecha de fusión
                            </label>

                            <input
                                id="fecha_fusion"
                                type="datetime-local"
                                name="fecha_fusion"
                                value="{{ old(
                                    'fecha_fusion',
                                    now()->format('Y-m-d\TH:i')
                                ) }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label
                                for="observacion"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Motivo u observación
                            </label>

                            <textarea
                                id="observacion"
                                name="observacion"
                                rows="4"
                                maxlength="2000"
                                placeholder="Ejemplo: Reunificación de lotes ubicados en el mismo ambiente."
                                class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >{{ old('observacion') }}</textarea>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-800">
                            Las cantidades de todos los lotes seleccionados pasarán al lote principal.
                        </p>

                        <p class="mt-1 text-sm text-amber-700">
                            El lote secundario quedará con cantidad cero, pero no será eliminado.
                        </p>
                    </div>

                    <button
                        type="submit"
                        onclick="return confirm('¿Confirmas la fusión de estos lotes?')"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700 sm:w-auto"
                    >
                        Fusionar lotes
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection