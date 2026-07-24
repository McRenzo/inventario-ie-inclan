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
                    Movimientos
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    Transferir bien
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Cambia el área, ubicación o responsable del activo.
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
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-700">
                        {{ $tipo === 'unidad' ? 'Unidad individual' : 'Lote' }}
                    </span>

                    <h2 class="mt-3 text-xl font-bold text-slate-900">
                        {{ $bien?->nombre ?? 'Bien sin nombre' }}
                    </h2>

                    <p class="mt-1 font-mono text-sm font-semibold text-slate-500">
                        {{ $elemento->codigo_interno }}
                    </p>
                </div>

                <div class="grid gap-3 text-sm sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-500">Área actual</p>
                        <p class="font-semibold text-slate-800">
                            {{ $elemento->area?->nombre ?? 'Sin área' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-500">Ubicación actual</p>
                        <p class="font-semibold text-slate-800">
                            {{ $elemento->ubicacion?->nombre ?? 'Sin ubicación' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-500">Responsable actual</p>
                        <p class="font-semibold text-slate-800">
                            {{ $elemento->responsable_nombre ?: 'Sin responsable' }}
                        </p>
                    </div>

                    @if ($tipo === 'lote')
                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-500">Cantidad disponible</p>
                            <p class="font-semibold text-slate-800">
                                {{ rtrim(rtrim(number_format((float) $lote->cantidad_actual, 2, '.', ''), '0'), '.') }}
                                {{ $lote->unidad_medida }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('v2.transferencias.store') }}"
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
                        Destino de la transferencia
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Selecciona el área y ubicación donde quedará el bien.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    @if ($tipo === 'lote')
                        <div class="md:col-span-2">
                            <label
                                for="cantidad"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Cantidad a transferir
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="cantidad"
                                type="number"
                                name="cantidad"
                                value="{{ old('cantidad', $lote->cantidad_actual) }}"
                                min="0.01"
                                max="{{ $lote->cantidad_actual }}"
                                step="0.01"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                Si transfieres una cantidad menor, el sistema creará un lote nuevo para el destino.
                            </p>
                        </div>
                    @endif

                    <div>
                        <label
                            for="area_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Área de destino
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="area_id"
                            name="area_id"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">
                                Selecciona un área
                            </option>

                            @foreach ($areas as $area)
                                <option
                                    value="{{ $area->id }}"
                                    @selected(
                                        old('area_id', $elemento->area_id) == $area->id
                                    )
                                >
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            for="ubicacion_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Ubicación de destino
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="ubicacion_id"
                            name="ubicacion_id"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">
                                Selecciona una ubicación
                            </option>

                            @foreach ($ubicaciones as $ubicacion)
                                <option
                                    value="{{ $ubicacion->id }}"
                                    @selected(
                                        old('ubicacion_id', $elemento->ubicacion_id) == $ubicacion->id
                                    )
                                >
                                    {{ $ubicacion->nombre }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div>
                        <label
                            for="fecha_transferencia"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Fecha de transferencia
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="fecha_transferencia"
                            type="datetime-local"
                            name="fecha_transferencia"
                            value="{{ old('fecha_transferencia', now()->format('Y-m-d\TH:i')) }}"
                            required
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
                            placeholder="Ejemplo: Transferencia por cambio de ambiente o reasignación."
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >{{ old('observacion') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Nuevo responsable
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Déjalo vacío si el bien quedará disponible y sin asignación.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label
                            for="responsable_nombre"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nombre completo
                        </label>

                        <input
                            id="responsable_nombre"
                            type="text"
                            name="responsable_nombre"
                            value="{{ old('responsable_nombre', $elemento->responsable_nombre) }}"
                            maxlength="255"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="responsable_dni"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            DNI
                        </label>

                        <input
                            id="responsable_dni"
                            type="text"
                            name="responsable_dni"
                            value="{{ old('responsable_dni', $elemento->responsable_dni) }}"
                            maxlength="20"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="responsable_telefono"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Teléfono
                        </label>

                        <input
                            id="responsable_telefono"
                            type="text"
                            name="responsable_telefono"
                            value="{{ old('responsable_telefono', $elemento->responsable_telefono) }}"
                            maxlength="30"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="responsable_cargo"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Cargo
                        </label>

                        <input
                            id="responsable_cargo"
                            type="text"
                            name="responsable_cargo"
                            value="{{ old('responsable_cargo', $elemento->responsable_cargo) }}"
                            maxlength="255"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="responsable_area"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Área del responsable
                        </label>

                        <input
                            id="responsable_area"
                            type="text"
                            name="responsable_area"
                            value="{{ old('responsable_area', $elemento->responsable_area) }}"
                            maxlength="255"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
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
                    Confirmar transferencia
                </button>
            </div>
        </form>
    </div>

@endsection