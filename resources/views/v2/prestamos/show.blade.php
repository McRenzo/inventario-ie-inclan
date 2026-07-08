@extends('layouts.logistica')

@section('content')
    @php
        $esUnidad = !is_null($prestamo->unidad_bien_id);
        $elemento = $esUnidad ? $prestamo->unidad : $prestamo->lote;
        $bien = $elemento?->bien;
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Préstamos
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    {{ $prestamo->codigo }}
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Detalle del préstamo registrado.
                </p>
            </div>

            <a
                href="{{ $esUnidad
                    ? route('v2.unidades.show', $prestamo->unidad)
                    : route('v2.lotes.show', $prestamo->lote) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Volver al bien
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

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

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide
                                {{ $prestamo->estado === 'devuelto'
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : ($prestamo->estado === 'vencido'
                                        ? 'bg-red-50 text-red-700'
                                        : 'bg-amber-50 text-amber-700') }}"
                            >
                                {{ ucfirst($prestamo->estado) }}
                            </span>

                            <h2 class="mt-3 text-xl font-bold text-slate-900">
                                {{ $bien?->nombre ?? 'Bien sin nombre' }}
                            </h2>

                            <p class="mt-1 font-mono text-sm font-semibold text-slate-500">
                                {{ $elemento?->codigo_interno }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm">
                            <p class="text-xs text-slate-500">Tipo</p>
                            <p class="font-semibold text-slate-800">
                                {{ $esUnidad ? 'Unidad individual' : 'Lote' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Área
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $elemento?->area?->nombre ?? 'Sin área' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Ubicación
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $elemento?->ubicacion?->nombre ?? 'Sin ubicación' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Cantidad
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $esUnidad
                                    ? '1 unidad'
                                    : rtrim(rtrim(number_format((float) $prestamo->cantidad, 2, '.', ''), '0'), '.') . ' ' . $elemento?->unidad_medida }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Conservación al salir
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->estadoConservacionSalida?->nombre ?? 'Sin registrar' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">
                        Datos del receptor
                    </h2>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Nombre
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->receptor_nombre }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                DNI
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->receptor_dni ?: 'No registrado' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Cargo
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->receptor_cargo ?: 'No registrado' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Área
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->receptor_area ?: 'No registrada' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Teléfono
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->receptor_telefono ?: 'No registrado' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">
                        Fechas y observaciones
                    </h2>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Fecha del préstamo
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->fecha_prestamo?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Devolución prevista
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $prestamo->fecha_devolucion_prevista?->format('d/m/Y H:i') ?? 'Sin fecha prevista' }}
                            </p>
                        </div>

                        @if ($prestamo->fecha_devolucion_real)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Devolución real
                                </p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    {{ $prestamo->fecha_devolucion_real->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Observaciones de salida
                        </p>
                        <p class="mt-2 whitespace-pre-line text-sm text-slate-700">
                            {{ $prestamo->observaciones_salida ?: 'Sin observaciones' }}
                        </p>
                    </div>

                    @if ($prestamo->estado === 'devuelto')
                        <div class="mt-5 border-t border-slate-100 pt-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Observaciones de devolución
                            </p>
                            <p class="mt-2 whitespace-pre-line text-sm text-slate-700">
                                {{ $prestamo->observaciones_devolucion ?: 'Sin observaciones' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">
                        Registro
                    </h2>

                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Registrado por</p>
                            <p class="font-semibold text-slate-800">
                                {{ $prestamo->registradoPor?->name ?? 'Usuario no disponible' }}
                            </p>
                        </div>

                        @if ($prestamo->devueltoPor)
                            <div>
                                <p class="text-xs text-slate-500">Devuelto por</p>
                                <p class="font-semibold text-slate-800">
                                    {{ $prestamo->devueltoPor->name }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                @if (in_array($prestamo->estado, ['activo', 'vencido'], true))
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Registrar devolución
                        </h2>

                        <p class="mt-1 text-sm text-slate-600">
                            Confirma el retorno del bien al inventario.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('v2.prestamos.devolver', $prestamo) }}"
                            class="mt-5 space-y-4"
                        >
                            @csrf
                            @method('PUT')

                            <div>
                                <label
                                    for="fecha_devolucion_real"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Fecha de devolución
                                </label>

                                <input
                                    id="fecha_devolucion_real"
                                    type="datetime-local"
                                    name="fecha_devolucion_real"
                                    value="{{ old('fecha_devolucion_real', now()->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>

                            <div>
                                <label
                                    for="estado_conservacion_devolucion_id"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Conservación al devolver
                                </label>

                                <select
                                    id="estado_conservacion_devolucion_id"
                                    name="estado_conservacion_devolucion_id"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                                    <option value="">
                                        Mantener estado actual
                                    </option>

                                    @foreach ($estadosConservacion as $estado)
                                        <option
                                            value="{{ $estado->id }}"
                                            @selected(old('estado_conservacion_devolucion_id') == $estado->id)
                                        >
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    for="observaciones_devolucion"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Observaciones
                                </label>

                                <textarea
                                    id="observaciones_devolucion"
                                    name="observaciones_devolucion"
                                    rows="4"
                                    class="w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >{{ old('observaciones_devolucion') }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
                            >
                                Confirmar devolución
                            </button>
                        </form>
                    </div>
                @else
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
                        <p class="font-bold text-emerald-800">
                            Préstamo devuelto
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            El bien ya fue reincorporado al inventario.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection