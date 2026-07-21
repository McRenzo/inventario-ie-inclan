@extends('layouts.logistica')

@section('title', 'Importar inventario V2')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <p class="text-xs font-black uppercase tracking-[0.25em] text-blue-600">
                Importación
            </p>

            <h1 class="mt-2 text-2xl font-black text-slate-900">
                Importar inventario desde Excel
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Sube la plantilla adaptada del inventario V2. Para el archivo de Comunicaciones,
                todos los registros se importarán como unidades individuales.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-black">No se pudo importar el archivo.</p>

                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('v2.bienes.importar') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5"
        >
            @csrf

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Archivo Excel
                </label>

                <input
                    type="file"
                    name="excel_file"
                    accept=".xlsx,.xls,.csv"
                    required
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-bold file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                >

                <p class="mt-2 text-xs text-slate-400">
                    Formatos permitidos: .xlsx, .xls, .csv. Tamaño máximo: 10 MB.
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                <p class="font-bold text-slate-800">
                    Columnas esperadas:
                </p>

                <p class="mt-2">
                    tipo_registro, area, ubicacion, nombre_bien, descripcion,
                    cantidad, numero_serie, estado_conservacion, estado_operatividad,
                    procedencia, fecha_ingreso, anio_ingreso, valor_unitario,
                    observaciones, archivo_origen, fila_origen.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a
                    href="{{ route('v2.bienes.index') }}"
                    class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-black text-slate-600 transition hover:bg-slate-50"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    Importar inventario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection