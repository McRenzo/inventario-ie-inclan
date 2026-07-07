@extends('layouts.logistica')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Inventario institucional
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Editar unidad individual
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $unidad->codigo_interno }}
            </p>
        </div>

        <a
            href="{{ route('v2.unidades.show', $unidad) }}"
            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
        >
            Volver al detalle
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
            <p class="font-bold text-red-700">
                Revisa los datos ingresados.
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('v2.unidades.update', $unidad) }}"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Identificación
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    El código interno se conserva y no puede modificarse.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Código interno
                    </label>

                    <input
                        type="text"
                        value="{{ $unidad->codigo_interno }}"
                        disabled
                        class="w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-500"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Ficha del bien
                    </label>

                    <select
                        name="bien_id"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        @foreach ($bienes as $bien)
                            <option
                                value="{{ $bien->id }}"
                                @selected(
                                    old('bien_id', $unidad->bien_id) == $bien->id
                                )
                            >
                                {{ $bien->nombre }}

                                @if ($bien->marca || $bien->modelo)
                                    — {{ trim(($bien->marca ?? '') . ' ' . ($bien->modelo ?? '')) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Número de serie
                    </label>

                    <input
                        type="text"
                        name="numero_serie"
                        value="{{ old('numero_serie', $unidad->numero_serie) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Código patrimonial oficial
                    </label>

                    <input
                        type="text"
                        name="codigo_patrimonial"
                        value="{{ old('codigo_patrimonial', $unidad->codigo_patrimonial) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Ubicación y estado
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Área
                    </label>

                    <select
                        name="area_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Sin área</option>

                        @foreach ($areas as $area)
                            <option
                                value="{{ $area->id }}"
                                @selected(
                                    old('area_id', $unidad->area_id) == $area->id
                                )
                            >
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Ubicación
                    </label>

                    <select
                        name="ubicacion_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Sin ubicación</option>

                        @foreach ($ubicaciones as $ubicacion)
                            <option
                                value="{{ $ubicacion->id }}"
                                @selected(
                                    old('ubicacion_id', $unidad->ubicacion_id) == $ubicacion->id
                                )
                            >
                                {{ $ubicacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Estado de conservación
                    </label>

                    <select
                        name="estado_conservacion_id"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        @foreach ($estadosConservacion as $estado)
                            <option
                                value="{{ $estado->id }}"
                                @selected(
                                    old(
                                        'estado_conservacion_id',
                                        $unidad->estado_conservacion_id
                                    ) == $estado->id
                                )
                            >
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Estado de operatividad
                    </label>

                    <select
                        name="estado_operatividad_id"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">No determinado</option>

                        @foreach ($estadosOperatividad as $estado)
                            <option
                                value="{{ $estado->id }}"
                                @selected(
                                    old(
                                        'estado_operatividad_id',
                                        $unidad->estado_operatividad_id
                                    ) == $estado->id
                                )
                            >
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Situación
                    </label>

                    <select
                        name="situacion"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option
                            value="disponible"
                            @selected(
                                old('situacion', $unidad->situacion) === 'disponible'
                            )
                        >
                            Disponible
                        </option>

                        <option
                            value="asignado"
                            @selected(
                                old('situacion', $unidad->situacion) === 'asignado'
                            )
                        >
                            Asignado
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Responsable
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Al modificar estos datos se registrará un cambio de responsable.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Nombres y apellidos
                    </label>

                    <input
                        type="text"
                        name="responsable_nombre"
                        value="{{ old('responsable_nombre', $unidad->responsable_nombre) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        DNI
                    </label>

                    <input
                        type="text"
                        name="responsable_dni"
                        maxlength="20"
                        value="{{ old('responsable_dni', $unidad->responsable_dni) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Cargo
                    </label>

                    <input
                        type="text"
                        name="responsable_cargo"
                        value="{{ old('responsable_cargo', $unidad->responsable_cargo) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Área del responsable
                    </label>

                    <input
                        type="text"
                        name="responsable_area"
                        value="{{ old('responsable_area', $unidad->responsable_area) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="responsable_telefono"
                        value="{{ old('responsable_telefono', $unidad->responsable_telefono) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Fechas y valorización
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Fecha de adquisición
                    </label>

                    <input
                        type="date"
                        name="fecha_adquisicion"
                        value="{{ old(
                            'fecha_adquisicion',
                            $unidad->fecha_adquisicion?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Fecha de ingreso
                    </label>

                    <input
                        type="date"
                        name="fecha_ingreso"
                        value="{{ old(
                            'fecha_ingreso',
                            $unidad->fecha_ingreso?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Fecha de puesta en uso
                    </label>

                    <input
                        type="date"
                        name="fecha_puesta_en_uso"
                        value="{{ old(
                            'fecha_puesta_en_uso',
                            $unidad->fecha_puesta_en_uso?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Año de ingreso
                    </label>

                    <input
                        type="number"
                        name="anio_ingreso"
                        value="{{ old('anio_ingreso', $unidad->anio_ingreso) }}"
                        min="1900"
                        max="{{ now()->year }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor de adquisición
                    </label>

                    <input
                        type="number"
                        name="valor_adquisicion"
                        value="{{ old('valor_adquisicion', $unidad->valor_adquisicion) }}"
                        min="0"
                        step="0.01"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor residual
                    </label>

                    <input
                        type="number"
                        name="valor_residual"
                        value="{{ old('valor_residual', $unidad->valor_residual) }}"
                        min="0"
                        step="0.01"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Proveedor
                    </label>

                    <input
                        type="text"
                        name="proveedor"
                        value="{{ old('proveedor', $unidad->proveedor) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Tipo de comprobante
                    </label>

                    <input
                        type="text"
                        name="tipo_comprobante"
                        value="{{ old('tipo_comprobante', $unidad->tipo_comprobante) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Número de comprobante
                    </label>

                    <input
                        type="text"
                        name="numero_comprobante"
                        value="{{ old('numero_comprobante', $unidad->numero_comprobante) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div class="md:col-span-2 xl:col-span-3">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >{{ old('observaciones', $unidad->observaciones) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a
                href="{{ route('v2.unidades.show', $unidad) }}"
                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection