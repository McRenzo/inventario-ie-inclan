<x-logistica-layout>
    <div class="w-full space-y-6">
        <div>
            <p class="text-sm font-semibold text-blue-600">
                Resumen general
            </p>

            <h1 class="text-2xl font-black text-slate-900">
                Dashboard
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Estado actual del inventario y los préstamos.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Total de activos
                </p>

                <p class="mt-2 text-3xl font-black text-slate-900">
                    {{ $data['totalActivos'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Unidades
                </p>

                <p class="mt-2 text-3xl font-black text-blue-600">
                    {{ $data['totalUnidades'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Lotes
                </p>

                <p class="mt-2 text-3xl font-black text-violet-600">
                    {{ $data['totalLotes'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Disponibles
                </p>

                <p class="mt-2 text-3xl font-black text-emerald-600">
                    {{ $data['activosDisponibles'] }}
                </p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <a
                href="{{ route('v2.prestamos.index', ['estado' => 'activo']) }}"
                class="rounded-2xl border border-amber-200 bg-amber-50 p-5 transition hover:-translate-y-0.5 hover:shadow-md"
            >
                <p class="text-xs font-bold uppercase tracking-wide text-amber-700">
                    Préstamos activos
                </p>

                <p class="mt-2 text-3xl font-black text-amber-700">
                    {{ $data['prestamosActivos'] }}
                </p>
            </a>

            <a
                href="{{ route('v2.prestamos.index', ['estado' => 'vencido']) }}"
                class="rounded-2xl border border-red-200 bg-red-50 p-5 transition hover:-translate-y-0.5 hover:shadow-md"
            >
                <p class="text-xs font-bold uppercase tracking-wide text-red-700">
                    Préstamos vencidos
                </p>

                <p class="mt-2 text-3xl font-black text-red-700">
                    {{ $data['prestamosVencidos'] }}
                </p>
            </a>

            <a
                href="{{ route('v2.prestamos.index', ['estado' => 'devuelto']) }}"
                class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 transition hover:-translate-y-0.5 hover:shadow-md"
            >
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">
                    Préstamos devueltos
                </p>

                <p class="mt-2 text-3xl font-black text-emerald-700">
                    {{ $data['prestamosDevueltos'] }}
                </p>
            </a>

            <a
                href="{{ route('v2.prestamos.index', ['estado' => 'cancelado']) }}"
                class="rounded-2xl border border-slate-200 bg-slate-100 p-5 transition hover:-translate-y-0.5 hover:shadow-md"
            >
                <p class="text-xs font-bold uppercase tracking-wide text-slate-600">
                    Préstamos cancelados
                </p>

                <p class="mt-2 text-3xl font-black text-slate-700">
                    {{ $data['prestamosCancelados'] }}
                </p>
            </a>
        </div>

        <div class="grid w-full gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Últimos préstamos
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Movimientos registrados recientemente.
                        </p>
                    </div>

                    <a
                        href="{{ route('v2.prestamos.index') }}"
                        class="text-sm font-bold text-blue-600 hover:text-blue-700"
                    >
                        Ver todos
                    </a>
                </div>

                <div class="mt-5 grid gap-3 md:grid-cols-2">
                    @forelse ($data['ultimosPrestamos'] as $prestamo)
                        @php
                            $elemento = $prestamo->unidad ?? $prestamo->lote;
                            $bien = $elemento?->bien;

                            $claseEstado = match ($prestamo->estado) {
                                'activo' => 'bg-amber-50 text-amber-700',
                                'vencido' => 'bg-red-50 text-red-700',
                                'devuelto' => 'bg-emerald-50 text-emerald-700',
                                'cancelado' => 'bg-slate-100 text-slate-600',
                                default => 'bg-slate-100 text-slate-600',
                            };
                        @endphp

                        <a
                            href="{{ route('v2.prestamos.show', $prestamo) }}"
                            class="flex flex-col gap-3 rounded-xl border border-slate-100 p-4 transition hover:border-blue-200 hover:bg-blue-50/40"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-mono text-xs font-bold text-blue-600">
                                        {{ $prestamo->codigo }}
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        {{ $bien?->nombre ?? 'Bien sin nombre' }}
                                    </p>
                                </div>

                                <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $claseEstado }}">
                                    {{ ucfirst($prestamo->estado) }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-500">
                                {{ $prestamo->receptor_nombre }}
                                ·
                                {{ $prestamo->fecha_prestamo?->format('d/m/Y H:i') }}
                            </p>
                        </a>
                    @empty
                        <div class="rounded-xl bg-slate-50 px-4 py-10 text-center text-sm text-slate-500 md:col-span-2">
                            Todavía no hay préstamos registrados.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Resumen de préstamos
                </h2>

                <div class="mt-5 space-y-4">
                    <div class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-3">
                        <span class="text-sm font-semibold text-amber-700">
                            Activos
                        </span>

                        <span class="text-lg font-black text-amber-700">
                            {{ $data['prestamosActivos'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-red-50 px-4 py-3">
                        <span class="text-sm font-semibold text-red-700">
                            Vencidos
                        </span>

                        <span class="text-lg font-black text-red-700">
                            {{ $data['prestamosVencidos'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-emerald-50 px-4 py-3">
                        <span class="text-sm font-semibold text-emerald-700">
                            Devueltos
                        </span>

                        <span class="text-lg font-black text-emerald-700">
                            {{ $data['prestamosDevueltos'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-100 px-4 py-3">
                        <span class="text-sm font-semibold text-slate-700">
                            Cancelados
                        </span>

                        <span class="text-lg font-black text-slate-700">
                            {{ $data['prestamosCancelados'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-logistica-layout>