<x-logistica-layout>
    <div class="max-w-5xl mx-auto p-6 no-print">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-black text-slate-800">Vista previa de etiquetas</h1>
            <div class="flex gap-3">
                <a href="{{ route('bienes.index') }}" class="px-4 py-2 bg-gray-200 rounded-xl text-xs font-bold text-slate-700">← Volver al Inventario</a>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold">Imprimir etiquetas</button>
            </div>
        </div>
    </div>

    <div class="contenedor-etiquetas flex flex-wrap gap-6 justify-center max-w-5xl mx-auto px-6">
        @foreach($bienes as $bien)
            <div class="tarjeta-etiqueta bg-white p-6 rounded-2xl border border-gray-200 shadow-sm w-[400px]">
                <div class="flex items-center gap-4 mb-4 border-b pb-4 header-etiqueta">
                    <img src="{{ asset('images/logo.png') }}" class="w-12 h-12 object-contain">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Inventario Inclán</p>
                        <p class="text-xs font-black text-slate-800 leading-tight uppercase">{{ $bien->nombre }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 cuerpo-etiqueta">
                    <div class="flex-shrink-0 qr-contenedor">{!! QrCode::size(100)->generate($bien->codigo_barras_qr) !!}</div>
                    <div class="flex-grow text-center barras-contenedor">
                        <img src="data:image/png;base64,{{ base64_encode((new \Picqer\Barcode\BarcodeGeneratorPNG())->getBarcode($bien->codigo_barras_qr, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128)) }}" class="w-full h-16 img-barras">
                        <p class="text-xs font-mono font-bold mt-2">{{ $bien->codigo_barras_qr }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
    /* Configuración de la página de impresión */
    @page { 
        size: A4 portrait; 
        margin: 10mm; /* Deja margen para las etiquetas */
    }

    /* Reglas exclusivas para la impresión */
    @media print {
        /* Oculta los botones, el HEADER del sistema y el FOOTER del sistema */
        .no-print, 
        header, 
        footer { 
            display: none !important; 
            height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Fuerza al contenedor a usar Grid de exactamente 3 columnas */
        .contenedor-etiquetas {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important; 
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-w: none !important;
        }

        /* AJUSTE RECTANGULAR SIN ESPACIOS VACÍOS */
        .tarjeta-etiqueta {
            width: 100% !important;
            height: auto !important; 
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important; 
            background-color: #fff !important;
            padding: 10px !important; 
            page-break-inside: avoid;
            display: block !important; 
            box-sizing: border-box !important;
        }

        /* Encabezado interno de la etiqueta */
        .header-etiqueta {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin-bottom: 8px !important;
            padding-bottom: 6px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .header-etiqueta img {
            width: 28px !important;
            height: 28px !important;
            object-fit: contain !important;
        }

        .header-etiqueta p {
            font-size: 9px !important;
            line-height: 1.1 !important;
        }

        /* Cuerpo de la etiqueta */
        .cuerpo-etiqueta {
            display: flex !important;
            flex-direction: row !important; 
            align-items: center !important;
            justify-content: space-between !important;
            gap: 6px !important;
        }

        .qr-contenedor svg {
            width: 50px !important; 
            height: 50px !important;
        }

        .barras-contenedor {
            flex-grow: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .img-barras {
            height: 32px !important; 
            width: 100% !important;
            max-width: 110px !important; 
            object-fit: fill !important;
        }

        .barras-contenedor p {
            font-size: 8px !important;
            font-family: monospace !important;
            margin-top: 2px !important;
        }
    }
</style>
</x-logistica-layout>