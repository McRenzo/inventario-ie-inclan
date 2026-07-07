@extends('layouts.logistica')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                Inventario institucional
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Editar lote
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $lote->codigo_interno }}
            </p>
        </div>

        <a
            href="{{ route('v2.lotes.show', $lote) }}"
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
        action="{{ route('v2.lotes.update', $lote) }}"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Identificación del lote
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    El código interno y la cantidad inicial no pueden modificarse.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Código interno
                    </label>

                    <input
                        type="text"
                        value="{{ $lote->codigo_interno }}"
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                        @foreach ($bienes as $bien)
                            <option
                                value="{{ $bien->id }}"
                                @selected(
                                    old('bien_id', $lote->bien_id) == $bien->id
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
                        Cantidad inicial
                    </label>

                    <input
                        type="number"
                        value="{{ $lote->cantidad_inicial }}"
                        disabled
                        class="w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-500"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Cantidad actual
                    </label>

                    <input
                        type="number"
                        name="cantidad_actual"
                        value="{{ old('cantidad_actual', $lote->cantidad_actual) }}"
                        min="0"
                        step="0.01"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >

                    <p class="mt-2 text-xs text-slate-400">
                        Si modificas esta cantidad, se registrará un ajuste en el historial.
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Unidad de medida
                    </label>

                    <input
                        type="text"
                        name="unidad_medida"
                        value="{{ old('unidad_medida', $lote->unidad_medida) }}"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                        <option value="">Sin área</option>

                        @foreach ($areas as $area)
                            <option
                                value="{{ $area->id }}"
                                @selected(
                                    old('area_id', $lote->area_id) == $area->id
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                        <option value="">Sin ubicación</option>

                        @foreach ($ubicaciones as $ubicacion)
                            <option
                                value="{{ $ubicacion->id }}"
                                @selected(
                                    old('ubicacion_id', $lote->ubicacion_id) == $ubicacion->id
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                        @foreach ($estadosConservacion as $estado)
                            <option
                                value="{{ $estado->id }}"
                                @selected(
                                    old(
                                        'estado_conservacion_id',
                                        $lote->estado_conservacion_id
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                        <option value="">No determinado</option>

                        @foreach ($estadosOperatividad as $estado)
                            <option
                                value="{{ $estado->id }}"
                                @selected(
                                    old(
                                        'estado_operatividad_id',
                                        $lote->estado_operatividad_id
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                        <option
                            value="disponible"
                            @selected(
                                old('situacion', $lote->situacion) === 'disponible'
                            )
                        >
                            Disponible
                        </option>

                        <option
                            value="asignado"
                            @selected(
                                old('situacion', $lote->situacion) === 'asignado'
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
                        value="{{ old('responsable_nombre', $lote->responsable_nombre) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
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
                        value="{{ old('responsable_dni', $lote->responsable_dni) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Cargo
                    </label>

                    <input
                        type="text"
                        name="responsable_cargo"
                        value="{{ old('responsable_cargo', $lote->responsable_cargo) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Área del responsable
                    </label>

                    <input
                        type="text"
                        name="responsable_area"
                        value="{{ old('responsable_area', $lote->responsable_area) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="responsable_telefono"
                        value="{{ old('responsable_telefono', $lote->responsable_telefono) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Fechas y valorización
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    El valor total se recalculará usando la cantidad actual y el valor unitario.
                </p>
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
                            $lote->fecha_adquisicion?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
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
                            $lote->fecha_ingreso?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
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
                            $lote->fecha_puesta_en_uso?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Año de ingreso
                    </label>

                    <input
                        type="number"
                        name="anio_ingreso"
                        value="{{ old('anio_ingreso', $lote->anio_ingreso) }}"
                        min="1900"
                        max="{{ now()->year }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor unitario
                    </label>

                    <input
                        type="number"
                        name="valor_unitario"
                        value="{{ old('valor_unitario', $lote->valor_unitario) }}"
                        min="0"
                        step="0.01"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor residual
                    </label>

                    <input
                        type="number"
                        name="valor_residual"
                        value="{{ old('valor_residual', $lote->valor_residual) }}"
                        min="0"
                        step="0.01"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Proveedor
                    </label>

                    <input
                        type="text"
                        name="proveedor"
                        value="{{ old('proveedor', $lote->proveedor) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Tipo de comprobante
                    </label>

                    <input
                        type="text"
                        name="tipo_comprobante"
                        value="{{ old('tipo_comprobante', $lote->tipo_comprobante) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Número de comprobante
                    </label>

                    <input
                        type="text"
                        name="numero_comprobante"
                        value="{{ old('numero_comprobante', $lote->numero_comprobante) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >
                </div>

                <div class="md:col-span-2 xl:col-span-3">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    >{{ old('observaciones', $lote->observaciones) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a
                href="{{ route('v2.lotes.show', $lote) }}"
                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700"
            >
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection