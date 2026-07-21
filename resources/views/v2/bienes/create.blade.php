@extends('layouts.logistica')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Inventario institucional
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Nueva ficha de bien
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Registra la información general del tipo de bien.
            </p>
        </div>

        <a
            href="{{ route('v2.bienes.index') }}"
            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
        >
            Volver al inventario
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
        action="{{ route('v2.bienes.store') }}"
        class="space-y-6"
    >
        @csrf

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Información principal
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Define el nombre y el tipo de control del bien.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Nombre del bien
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Tipo de control
                    </label>

                    <select
                        name="tipo_control"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Selecciona una opción</option>

                        <option
                            value="individual"
                            @selected(old('tipo_control') === 'individual')
                        >
                            Individual
                        </option>

                        <option
                            value="lote"
                            @selected(old('tipo_control') === 'lote')
                        >
                            Lote
                        </option>

                        <option
                            value="consumible"
                            @selected(old('tipo_control') === 'consumible')
                        >
                            Consumible
                        </option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >{{ old('descripcion') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Características
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Marca
                    </label>

                    <input
                        type="text"
                        name="marca"
                        value="{{ old('marca') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Modelo
                    </label>

                    <input
                        type="text"
                        name="modelo"
                        value="{{ old('modelo') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Material
                    </label>

                    <input
                        type="text"
                        name="material"
                        value="{{ old('material') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Procedencia
                    </label>

                    <input
                        type="text"
                        name="procedencia"
                        value="{{ old('procedencia') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Nivel educativo
                    </label>

                    <input
                        type="text"
                        name="nivel_educativo"
                        value="{{ old('nivel_educativo') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Ciclo
                    </label>

                    <input
                        type="text"
                        name="ciclo"
                        value="{{ old('ciclo') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Valorización
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Configuración opcional para calcular la pérdida de valor del bien.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="inline-flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="es_depreciable"
                            value="1"
                            @checked(old('es_depreciable'))
                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >

                        <span class="text-sm font-bold text-slate-700">
                            Este bien es depreciable
                        </span>
                    </label>

                    <p class="mt-2 text-xs text-slate-400">
                        Marca esta opción solo si deseas controlar la depreciación del bien.
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Vida útil en meses
                    </label>

                    <input
                        type="number"
                        name="vida_util_meses"
                        value="{{ old('vida_util_meses') }}"
                        min="1"
                        max="1200"
                        placeholder="Ejemplo: 60"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor residual %
                    </label>

                    <input
                        type="number"
                        name="valor_residual_porcentaje"
                        value="{{ old('valor_residual_porcentaje', 0) }}"
                        min="0"
                        max="100"
                        step="0.01"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >{{ old('observaciones') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a
                href="{{ route('v2.bienes.index') }}"
                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Guardar ficha
            </button>
        </div>
    </form>
</div>
@endsection