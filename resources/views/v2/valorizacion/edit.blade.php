@extends('layouts.logistica')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Gestión patrimonial
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Valorizar unidad
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $unidad->codigo_interno }} · {{ $unidad->bien?->nombre ?? 'Sin ficha' }}
            </p>
        </div>

        <a
            href="{{ route('v2.valorizacion.index') }}"
            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
        >
            Volver
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

    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
        <h2 class="font-black text-slate-900">
            Información de la unidad
        </h2>

        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    Código
                </p>
                <p class="mt-1 font-bold text-blue-600">
                    {{ $unidad->codigo_interno }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    Bien
                </p>
                <p class="mt-1 font-bold text-slate-900">
                    {{ $unidad->bien?->nombre ?? 'Sin ficha' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    Serie
                </p>
                <p class="mt-1 text-slate-700">
                    {{ $unidad->numero_serie ?: 'Sin serie' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    Ubicación
                </p>
                <p class="mt-1 text-slate-700">
                    {{ $unidad->ubicacion?->nombre ?? 'Sin ubicación' }}
                </p>
            </div>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('v2.valorizacion.update', $unidad) }}"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="font-black text-slate-900">
                    Datos de valorización
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Ingresa los datos base. La depreciación acumulada y el valor en libros se calculan automáticamente.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor de adquisición
                    </label>

                    <input
                        type="number"
                        name="valor_adquisicion"
                        id="valor_adquisicion"
                        value="{{ old('valor_adquisicion', $unidad->valor_adquisicion) }}"
                        min="0"
                        step="0.01"
                        required
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
                        id="valor_residual"
                        value="{{ old('valor_residual', $unidad->valor_residual) }}"
                        min="0"
                        step="0.01"
                        placeholder="Ejemplo: 122.00"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >

                    <p class="mt-1 text-xs text-slate-400">
                        Valor mínimo que conservará el bien al finalizar su vida útil.
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Vida útil en meses
                    </label>

                    <input
                        type="number"
                        name="vida_util_meses"
                        id="vida_util_meses"
                        value="{{ old('vida_util_meses', $unidad->vida_util_meses) }}"
                        min="1"
                        max="1200"
                        placeholder="Ejemplo: 60"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor ajustado
                    </label>

                    <input
                        type="number"
                        name="valor_ajustado"
                        value="{{ old('valor_ajustado', $unidad->valor_ajustado) }}"
                        min="0"
                        step="0.01"
                        placeholder="Opcional"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    >

                    <p class="mt-1 text-xs text-slate-400">
                        Úsalo solo cuando quieras registrar un valor manual estimado.
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Depreciación acumulada
                    </label>

                    <input
                        type="text"
                        id="depreciacion_acumulada_preview"
                        value="S/ {{ number_format((float) ($unidad->depreciacion_acumulada ?? 0), 2) }}"
                        readonly
                        class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-700 outline-none"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Valor en libros
                    </label>

                    <input
                        type="text"
                        id="valor_en_libros_preview"
                        value="S/ {{ number_format((float) ($unidad->valor_en_libros ?? 0), 2) }}"
                        readonly
                        class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-700 outline-none"
                    >

                    <p class="mt-1 text-xs text-slate-400">
                        Este valor será calculado y guardado automáticamente.
                    </p>
                </div>

                <div class="md:col-span-2 rounded-2xl bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700">
                    <p>
                        El cálculo usa como fecha base:
                        {{ optional($unidad->fecha_adquisicion ?? $unidad->fecha_ingreso ?? $unidad->created_at)->format('d/m/Y') }}.
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Observaciones de valorización
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
                href="{{ route('v2.valorizacion.index') }}"
                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Guardar valorización
            </button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const valorAdquisicionInput = document.getElementById('valor_adquisicion');
        const valorResidualInput = document.getElementById('valor_residual');
        const vidaUtilInput = document.getElementById('vida_util_meses');

        const depreciacionPreview = document.getElementById('depreciacion_acumulada_preview');
        const valorLibrosPreview = document.getElementById('valor_en_libros_preview');

        const fechaBase = new Date('{{ optional($unidad->fecha_adquisicion ?? $unidad->fecha_ingreso ?? $unidad->created_at)->format('Y-m-d') }}');
        const hoy = new Date();

        function mesesTranscurridosEntre(fechaInicio, fechaFin) {
            let meses = (fechaFin.getFullYear() - fechaInicio.getFullYear()) * 12;
            meses += fechaFin.getMonth() - fechaInicio.getMonth();

            if (fechaFin.getDate() < fechaInicio.getDate()) {
                meses -= 1;
            }

            return Math.max(meses, 0);
        }

        function formatearSoles(valor) {
            return new Intl.NumberFormat('es-PE', {
                style: 'currency',
                currency: 'PEN',
                minimumFractionDigits: 2
            }).format(valor || 0);
        }

        function calcular() {
            const valorAdquisicion = parseFloat(valorAdquisicionInput.value) || 0;
            const valorResidual = parseFloat(valorResidualInput.value) || 0;
            const vidaUtil = parseInt(vidaUtilInput.value, 10) || 0;

            if (vidaUtil <= 0 || valorResidual > valorAdquisicion) {
                depreciacionPreview.value = formatearSoles(0);
                valorLibrosPreview.value = formatearSoles(valorAdquisicion);
                return;
            }

            const mesesTranscurridos = mesesTranscurridosEntre(fechaBase, hoy);
            const mesesDepreciados = Math.min(mesesTranscurridos, vidaUtil);
            const baseDepreciable = Math.max(valorAdquisicion - valorResidual, 0);
            const depreciacionMensual = baseDepreciable / vidaUtil;

            const depreciacionAcumulada = depreciacionMensual * mesesDepreciados;
            const valorEnLibros = Math.max(
                valorAdquisicion - depreciacionAcumulada,
                valorResidual
            );

            depreciacionPreview.value = formatearSoles(depreciacionAcumulada);
            valorLibrosPreview.value = formatearSoles(valorEnLibros);
        }

        valorAdquisicionInput.addEventListener('input', calcular);
        valorResidualInput.addEventListener('input', calcular);
        vidaUtilInput.addEventListener('input', calcular);

        calcular();
    });
</script>
@endsection