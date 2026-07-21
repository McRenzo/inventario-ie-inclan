@extends('layouts.logistica')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Parámetros
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    Ubicaciones
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Administra las ubicaciones disponibles del inventario.
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
                <ul class="list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Nueva ubicación
                </h2>

                <form
                    method="POST"
                    action="{{ route('v2.parametros.ubicaciones.store') }}"
                    class="mt-5 space-y-4"
                >
                    @csrf

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Nombre"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                    >

                    <input
                        type="text"
                        name="codigo"
                        value="{{ old('codigo') }}"
                        placeholder="Código opcional"
                        maxlength="50"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                    >

                    <textarea
                        name="descripcion"
                        rows="4"
                        placeholder="Descripción"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                    >{{ old('descripcion') }}</textarea>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white"
                    >
                        Crear ubicación
                    </button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                <div class="border-b border-slate-200 p-5">
                    <form
                        method="GET"
                        action="{{ route('v2.parametros.ubicaciones.index') }}"
                        class="flex gap-3"
                    >
                        <input
                            type="search"
                            name="buscar"
                            value="{{ $buscar }}"
                            placeholder="Buscar ubicación o código"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                        >

                        <button
                            type="submit"
                            class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white"
                        >
                            Buscar
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-500">
                                    Ubicación
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-500">
                                    Uso
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-500">
                                    Estado
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-bold uppercase text-slate-500">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($ubicaciones as $ubicacion)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ $ubicacion->nombre }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            {{ $ubicacion->codigo ?: 'Sin código' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        {{ $ubicacion->unidades_count }} unidades ·
                                        {{ $ubicacion->lotes_count }} lotes
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold
                                            {{ $ubicacion->activo
                                                ? 'bg-emerald-50 text-emerald-700'
                                                : 'bg-slate-100 text-slate-600' }}"
                                        >
                                            {{ $ubicacion->activo ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <details class="inline-block text-left">
                                            <summary class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700">
                                                Editar
                                            </summary>

                                            <div class="mt-3 w-80 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl">
                                                <form
                                                    method="POST"
                                                    action="{{ route('v2.parametros.ubicaciones.update', $ubicacion) }}"
                                                    class="space-y-3"
                                                >
                                                    @csrf
                                                    @method('PUT')

                                                    <input
                                                        type="text"
                                                        name="nombre"
                                                        value="{{ $ubicacion->nombre }}"
                                                        required
                                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                                    >

                                                    <input
                                                        type="text"
                                                        name="codigo"
                                                        value="{{ $ubicacion->codigo }}"
                                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                                    >

                                                    <textarea
                                                        name="descripcion"
                                                        rows="3"
                                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                                    >{{ $ubicacion->descripcion }}</textarea>

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white"
                                                    >
                                                        Guardar cambios
                                                    </button>
                                                </form>

                                                <form
                                                    method="POST"
                                                    action="{{ route('v2.parametros.ubicaciones.estado', $ubicacion) }}"
                                                    class="mt-3"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-xl px-4 py-2 text-sm font-bold
                                                            {{ $ubicacion->activo
                                                                ? 'bg-red-50 text-red-700'
                                                                : 'bg-emerald-50 text-emerald-700' }}"
                                                    >
                                                        {{ $ubicacion->activo
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
                                        No se encontraron ubicaciones.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($ubicaciones->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">
                        {{ $ubicaciones->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection