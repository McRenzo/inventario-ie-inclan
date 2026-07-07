@extends('layouts.logistica')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between print:hidden">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Inventario institucional
            </p>

            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                Etiquetas QR
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Vista previa de las etiquetas seleccionadas.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('v2.bienes.index') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
            >
                Volver al inventario
            </a>

            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
            >
                Imprimir etiquetas
            </button>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm print:border-0 print:p-0 print:shadow-none">
        <main class="sheet">
            @foreach ($etiquetas as $etiqueta)
                <article class="label">
                    <div class="institution">
                        <img
                            src="{{ asset('images/insignia-inclan.png') }}"
                            alt="Insignia de la IE José Joaquín Inclán"
                            class="institution-logo"
                        >

                        <div>
                            <p class="institution-name">
                                I.E. José Joaquín Inclán
                            </p>

                            <p class="institution-system">
                                Inventario Patrimonial
                            </p>
                        </div>
                    </div>

                    <div class="label-content">
                        <div class="qr">
                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                ->size(150)
                                ->margin(1)
                                ->generate($etiqueta['url']) !!}
                        </div>

                        <div>
                            <p class="type">
                                {{ $etiqueta['tipo'] === 'unidad'
                                    ? 'Unidad individual'
                                    : 'Lote' }}
                            </p>

                            <h2 class="code">
                                {{ $etiqueta['codigo'] }}
                            </h2>

                            <p class="name">
                                {{ $etiqueta['nombre'] }}
                            </p>

                            <div class="meta">
                                @if ($etiqueta['tipo'] === 'unidad')
                                    <div>
                                        Serie:
                                        {{ $etiqueta['serie'] ?: 'Sin registrar' }}
                                    </div>
                                @else
                                    <div>
                                        Cantidad:
                                        {{ number_format(
                                            (float) $etiqueta['cantidad'],
                                            2
                                        ) }}
                                        {{ $etiqueta['unidad_medida'] }}
                                    </div>
                                @endif

                                <div>
                                    Área:
                                    {{ $etiqueta['area'] ?: 'Sin área' }}
                                </div>

                                <div>
                                    Ubicación:
                                    {{ $etiqueta['ubicacion'] ?: 'Sin ubicación' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </main>
    </div>

</div>

<style>
    @page {
        size: A4;
        margin: 10mm;
    }

    .sheet {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 4mm;
        max-width: 190mm;
        margin: 0 auto;
    }

    .label {
        height: 32mm;
        padding: 2.5mm;
        overflow: hidden;
        background: white;
        border: 1px dashed #64748b;
        border-radius: 2.5mm;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .institution {
        display: flex;
        align-items: center;
        gap: 1.5mm;
        padding-bottom: 1.2mm;
        margin-bottom: 1.2mm;
        border-bottom: 1px solid #e2e8f0;
    }

    .institution-logo {
        width: 7mm;
        height: 7mm;
        object-fit: contain;
    }

    .institution-name {
        margin: 0;
        font-size: 6.5pt;
        font-weight: 800;
        line-height: 1.05;
        color: #0f172a;
    }

    .institution-system {
        margin: 0.5mm 0 0;
        font-size: 4.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    .label-content {
        display: grid;
        grid-template-columns: 19mm 1fr;
        align-items: center;
        gap: 2mm;
    }

    .qr {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr svg {
        width: 18mm;
        height: 18mm;
    }

    .type {
        margin: 0 0 0.5mm;
        font-size: 4.5pt;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .code {
        margin: 0;
        font-size: 8pt;
        font-weight: 800;
        line-height: 1.05;
    }

    .name {
        margin: 0.7mm 0 0;
        font-size: 6pt;
        font-weight: 700;
        line-height: 1.1;
    }

    .meta {
        margin: 0.7mm 0 0;
        font-size: 4.5pt;
        line-height: 1.15;
        color: #475569;
    }

    @media print {
        body {
            background: white !important;
        }

        aside,
        header,
        footer {
            display: none !important;
        }

        main {
            padding: 0 !important;
        }

        .lg\:pl-72 {
            padding-left: 0 !important;
        }

        .sheet {
            max-width: none;
            margin: 0;
        }
    }
</style>
@endsection