@extends('layouts.logistica')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Parámetros
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    Áreas
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Administra las áreas disponibles del inventario.
                </p>
            </div>

            <a
                href="{{ route('v2.parametros.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
            >
                Volver a parámetros
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

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Nueva área
                </h2>

                <form
                    method="POST"
                    action="{{ route('v2.parametros.areas.store') }}"
                    class="mt-5 space-y-4"
                >
                    @csrf

                    <div>
                        <label
                            for="nombre"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nombre
                        </label>

                        <input
                            id="nombre"
                            type="text"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            required
                            maxlength="255"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label
                            for="descripcion"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                            maxlength="1000"
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >{{ old('descripcion') }}</textarea>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
                    >
                        Crear área
                    </button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                <div class="border-b border-slate-200 p-5">
                    <form
                        method="GET"
                        action="{{ route('v2.parametros.areas.index') }}"
                        class="flex flex-col gap-3 sm:flex-row"
                    >
                        <input
                            type="search"
                            name="buscar"
                            value="{{ $buscar }}"
                            placeholder="Buscar área"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        <button
                            type="submit"
                            class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800"
                        >
                            Buscar
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Área
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Uso
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Estado
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($areas as $area)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ $area->nombre }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $area->descripcion ?: 'Sin descripción' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        <p>
                                            {{ $area->unidades_count }} unidades
                                        </p>
                                        <p>
                                            {{ $area->lotes_count }} lotes
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold
                                            {{ $area->activo
                                                ? 'bg-emerald-50 text-emerald-700'
                                                : 'bg-slate-100 text-slate-600' }}"
                                        >
                                            {{ $area->activo ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <details class="inline-block text-left">
                                            <summary class="cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700">
                                                Editar
                                            </summary>

                                            <div class="mt-3 w-80 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl">
                                                <form
                                                    method="POST"
                                                    action="{{ route('v2.parametros.areas.update', $area) }}"
                                                    class="space-y-3"
                                                >
                                                    @csrf
                                                    @method('PUT')

                                                    <input
                                                        type="text"
                                                        name="nombre"
                                                        value="{{ $area->nombre }}"
                                                        required
                                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                                    >

                                                    <textarea
                                                        name="descripcion"
                                                        rows="3"
                                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                                    >{{ $area->descripcion }}</textarea>

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white"
                                                    >
                                                        Guardar cambios
                                                    </button>
                                                </form>

                                                <form
                                                    method="POST"
                                                    action="{{ route('v2.parametros.areas.estado', $area) }}"
                                                    class="mt-3"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-xl px-4 py-2 text-sm font-bold
                                                            {{ $area->activo
                                                                ? 'bg-red-50 text-red-700'
                                                                : 'bg-emerald-50 text-emerald-700' }}"
                                                    >
                                                        {{ $area->activo
                                                            ? 'Desactivar'
                                                            : 'Activar' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-5 py-12 text-center text-sm text-slate-500"
                                    >
                                        No se encontraron áreas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($areas->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">
                        {{ $areas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection