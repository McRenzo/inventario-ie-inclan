@extends('layouts.logistica')

@section('content')
    @php
        $elemento = $tipo === 'unidad' ? $unidad : $lote;
        $bien = $elemento->bien;
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Préstamos
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    Registrar préstamo
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Registra la entrega temporal de un bien patrimonial.
                </p>
            </div>

            <a
                href="{{ $tipo === 'unidad'
                    ? route('v2.unidades.show', $unidad)
                    : route('v2.lotes.show', $lote) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-700">
                    Revisa los datos ingresados
                </p>

                <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-700">
                        {{ $tipo === 'unidad' ? 'Bien individual' : 'Lote' }}
                    </span>

                    <h2 class="mt-3 text-xl font-bold text-slate-900">
                        {{ $bien?->nombre ?? 'Bien sin nombre' }}
                    </h2>

                    <p class="mt-1 font-mono text-sm font-semibold text-slate-500">
                        {{ $elemento->codigo_interno }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-500">Área</p>
                        <p class="font-semibold text-slate-800">
                            {{ $elemento->area?->nombre ?? 'Sin área' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-500">Ubicación</p>
                        <p class="font-semibold text-slate-800">
                            {{ $elemento->ubicacion?->nombre ?? 'Sin ubicación' }}
                        </p>
                    </div>

                    @if ($tipo === 'unidad')
                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">Serie</p>
                            <p class="font-semibold text-slate-800">
                                {{ $unidad->numero_serie ?: 'Sin serie' }}
                            </p>
                        </div>
                    @else
                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">Disponible</p>
                            <p class="font-semibold text-slate-800">
                                {{ rtrim(rtrim(number_format((float) $lote->cantidad_actual, 2, '.', ''), '0'), '.') }}
                                {{ $lote->unidad_medida }}
                            </p>
                        </div>
                    @endif

                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-500">Conservación</p>
                        <p class="font-semibold text-slate-800">
                            {{ $elemento->estadoConservacion?->nombre ?? 'Sin registrar' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('v2.prestamos.store') }}"
            class="space-y-6"
        >
            @csrf

            <input
                type="hidden"
                name="tipo"
                value="{{ $tipo }}"
            >

            @if ($tipo === 'unidad')
                <input
                    type="hidden"
                    name="unidad_bien_id"
                    value="{{ $unidad->id }}"
                >
            @else
                <input
                    type="hidden"
                    name="lote_id"
                    value="{{ $lote->id }}"
                >
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Datos del receptor
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Persona que recibe temporalmente el bien.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label
                            for="receptor_nombre"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nombre completo
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="receptor_nombre"
                            type="text"
                            name="receptor_nombre"
                            value="{{ old('receptor_nombre') }}"
                            required
                            maxlength="255"
                            placeholder="Ejemplo: Juan Pérez López"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="receptor_dni"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            DNI
                        </label>

                        <input
                            id="receptor_dni"
                            type="text"
                            name="receptor_dni"
                            value="{{ old('receptor_dni') }}"
                            maxlength="20"
                            placeholder="Documento de identidad"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="receptor_telefono"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Teléfono
                        </label>

                        <input
                            id="receptor_telefono"
                            type="text"
                            name="receptor_telefono"
                            value="{{ old('receptor_telefono') }}"
                            maxlength="30"
                            placeholder="Número de contacto"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="receptor_cargo"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Cargo
                        </label>

                        <input
                            id="receptor_cargo"
                            type="text"
                            name="receptor_cargo"
                            value="{{ old('receptor_cargo') }}"
                            maxlength="255"
                            placeholder="Ejemplo: Docente"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="receptor_area"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Área
                        </label>

                        <input
                            id="receptor_area"
                            type="text"
                            name="receptor_area"
                            value="{{ old('receptor_area') }}"
                            maxlength="255"
                            placeholder="Ejemplo: Dirección"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Datos del préstamo
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Indica la fecha, cantidad y condiciones de entrega.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    @if ($tipo === 'lote')
                        <div>
                            <label
                                for="cantidad"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Cantidad a prestar
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="cantidad"
                                type="number"
                                name="cantidad"
                                value="{{ old('cantidad') }}"
                                required
                                min="0.01"
                                max="{{ $lote->cantidad_actual }}"
                                step="0.01"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                Máximo disponible:
                                {{ rtrim(rtrim(number_format((float) $lote->cantidad_actual, 2, '.', ''), '0'), '.') }}
                                {{ $lote->unidad_medida }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <label
                            for="fecha_prestamo"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Fecha del préstamo
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="fecha_prestamo"
                            type="datetime-local"
                            name="fecha_prestamo"
                            value="{{ old('fecha_prestamo', now()->format('Y-m-d\TH:i')) }}"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="fecha_devolucion_prevista"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Devolución prevista
                        </label>

                        <input
                            id="fecha_devolucion_prevista"
                            type="datetime-local"
                            name="fecha_devolucion_prevista"
                            value="{{ old('fecha_devolucion_prevista') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="estado_conservacion_salida_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Estado de conservación al salir
                        </label>

                        <select
                            id="estado_conservacion_salida_id"
                            name="estado_conservacion_salida_id"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">
                                Mantener estado actual
                            </option>

                            @foreach ($estadosConservacion as $estado)
                                <option
                                    value="{{ $estado->id }}"
                                    @selected(
                                        old(
                                            'estado_conservacion_salida_id',
                                            $elemento->estado_conservacion_id
                                        ) == $estado->id
                                    )
                                >
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="observaciones_salida"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Observaciones
                        </label>

                        <textarea
                            id="observaciones_salida"
                            name="observaciones_salida"
                            rows="4"
                            placeholder="Describe accesorios entregados, condiciones o indicaciones adicionales."
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >{{ old('observaciones_salida') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a
                    href="{{ $tipo === 'unidad'
                        ? route('v2.unidades.show', $unidad)
                        : route('v2.lotes.show', $lote) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    Registrar préstamo
                </button>
            </div>
        </form>
    </div>
@endsection