@extends('layouts.logistica')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Gestión patrimonial
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Valorización
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Actualiza los valores económicos de las unidades individuales del inventario.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('v2.valorizacion.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <input
                        type="search"
                        name="buscar"
                        value="{{ $busqueda }}"
                        placeholder="Buscar por código, serie, nombre, marca o modelo"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    Buscar
                </button>

                <a
                    href="{{ route('v2.valorizacion.index') }}"
                    class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
                >
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-black text-slate-900">
                Unidades para valorización
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                {{ $unidades->total() }} unidad(es) encontrada(s).
            </p>
        </div>

        <div class="overflow-x-auto max-w-full">
            <table class="w-full table-fixed">
                <colgroup>
                    <col class="w-[140px]">
                    <col class="w-[230px]">
                    <col class="w-[260px]">
                    <col class="w-[140px]">
                    <col class="w-[140px]">
                    <col class="w-[140px]">
                    <col class="w-[120px]">
                    <col class="w-[120px]">
                </colgroup>

                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-4">Código</th>
                        <th class="px-5 py-4">Bien</th>
                        <th class="px-5 py-4">Ubicación</th>
                        <th class="px-5 py-4 text-right">Adquisición</th>
                        <th class="px-5 py-4 text-right">Ajustado</th>
                        <th class="px-5 py-4 text-right">Libros</th>
                        <th class="px-5 py-4 text-center">Estado</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($unidades as $unidad)
                        @php
                            $tieneValor = (float) ($unidad->valor_adquisicion ?? 0) > 0;
                            $tieneAjuste = !is_null($unidad->valor_ajustado);

                            $estadoValorizacion = 'Pendiente';
                            $claseEstado = 'bg-amber-50 text-amber-700';

                            if ($tieneValor) {
                                $estadoValorizacion = 'Valorizado';
                                $claseEstado = 'bg-emerald-50 text-emerald-700';
                            }

                            if ($tieneAjuste) {
                                $estadoValorizacion = 'Ajustado';
                                $claseEstado = 'bg-blue-50 text-blue-700';
                            }
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4 align-top">
                                <span class="block break-words font-bold text-blue-600">
                                    {{ $unidad->codigo_interno }}
                                </span>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <p class="font-bold text-slate-900">
                                    {{ $unidad->bien?->nombre ?? 'Sin ficha' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400 whitespace-normal break-words">
                                    {{ trim(
                                        ($unidad->bien?->marca ?? '') . ' ' .
                                        ($unidad->bien?->modelo ?? '')
                                    ) ?: 'Sin marca o modelo' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 align-top text-slate-600">
                                <div class="whitespace-normal break-words text-sm leading-snug">
                                    {{ $unidad->ubicacion?->nombre ?? 'Sin ubicación' }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right font-bold text-slate-900">
                                S/ {{ number_format((float) ($unidad->valor_adquisicion ?? 0), 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right font-bold text-slate-900">
                                S/ {{ number_format((float) ($unidad->valor_ajustado ?? 0), 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right font-bold text-slate-900">
                                S/ {{ number_format((float) ($unidad->valor_en_libros ?? 0), 2) }}
                            </td>

                            <td class="px-5 py-4 text-center align-top">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $claseEstado }}">
                                    {{ $estadoValorizacion }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right align-top">
                                <a
                                    href="{{ route('v2.valorizacion.edit', $unidad) }}"
                                    class="inline-flex rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-700"
                                >
                                    Valorizar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <p class="font-semibold text-slate-500">
                                    No se encontraron unidades para valorizar.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($unidades->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $unidades->links() }}
            </div>
        @endif
    </div>

</div>
@endsection