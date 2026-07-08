@extends('layouts.logistica')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Control patrimonial
                </p>

                <h1 class="text-2xl font-bold text-slate-900">
                    Préstamos
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Consulta préstamos activos y registra devoluciones.
                </p>
            </div>

            <a
                href="{{ route('v2.bienes.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Ir al inventario
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('v2.prestamos.index') }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold
                        {{ $estado === ''
                            ? 'bg-blue-600 text-white'
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Todos
                </a>

                <a
                    href="{{ route('v2.prestamos.index', ['estado' => 'activo']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold
                        {{ $estado === 'activo'
                            ? 'bg-amber-500 text-white'
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Activos
                </a>

                <a
                    href="{{ route('v2.prestamos.index', ['estado' => 'devuelto']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold
                        {{ $estado === 'devuelto'
                            ? 'bg-emerald-600 text-white'
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Devueltos
                </a>

                <a
                    href="{{ route('v2.prestamos.index', ['estado' => 'vencido']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold
                        {{ $estado === 'vencido'
                            ? 'bg-red-600 text-white'
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Vencidos
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Código
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Bien
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Receptor
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Fecha
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Devolución prevista
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Estado
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-500">
                                Acción
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($prestamos as $prestamo)
                            @php
                                $elemento = $prestamo->unidad ?? $prestamo->lote;
                                $bien = $elemento?->bien;
                                $estaVencido = $prestamo->estado === 'activo'
                                    && $prestamo->fecha_devolucion_prevista
                                    && $prestamo->fecha_devolucion_prevista->isPast();
                            @endphp

                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-mono text-sm font-bold text-slate-800">
                                        {{ $prestamo->codigo }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $bien?->nombre ?? 'Bien sin nombre' }}
                                    </p>

                                    <p class="mt-1 font-mono text-xs text-slate-500">
                                        {{ $elemento?->codigo_interno }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $prestamo->receptor_nombre }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $prestamo->receptor_dni ?: 'Sin DNI' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                    {{ $prestamo->fecha_prestamo?->format('d/m/Y H:i') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm">
                                    @if ($prestamo->fecha_devolucion_prevista)
                                        <span class="{{ $estaVencido ? 'font-bold text-red-600' : 'text-slate-700' }}">
                                            {{ $prestamo->fecha_devolucion_prevista->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">
                                            Sin fecha
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($prestamo->estado === 'devuelto')
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                            Devuelto
                                        </span>
                                    @elseif ($estaVencido)
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                            Vencido
                                        </span>
                                    @elseif ($prestamo->estado === 'activo')
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                            {{ ucfirst($prestamo->estado) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <a
                                        href="{{ route('v2.prestamos.show', $prestamo) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-blue-700"
                                    >
                                        {{ $prestamo->estado === 'activo'
                                            ? 'Registrar devolución'
                                            : 'Ver detalle' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="7"
                                    class="px-5 py-12 text-center text-sm text-slate-500"
                                >
                                    No se encontraron préstamos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($prestamos->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $prestamos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection