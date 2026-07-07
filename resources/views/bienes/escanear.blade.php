<x-logistica-layout>
    <div class="mx-auto max-w-2xl space-y-6 p-6">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Inventario institucional
            </p>

            <h1 class="mt-1 text-2xl font-black text-slate-900">
                Escanear activo QR
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Escanea un código QR de una unidad o lote, o escribe el código interno manualmente.
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
                <p class="font-bold text-red-700">
                    No se pudo localizar el activo.
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div
                id="reader"
                class="w-full overflow-hidden rounded-2xl border-2 border-slate-200"
            ></div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <label class="mb-2 block text-sm font-bold text-slate-700">
                Código interno
            </label>

            <div class="flex flex-col gap-3 sm:flex-row">
                <input
                    id="codigoManual"
                    type="text"
                    placeholder="INC-IND-000001 o INC-LOT-000001"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm uppercase outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                >

                <button
                    type="button"
                    id="buscarCodigo"
                    class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    Buscar
                </button>
            </div>
        </div>

        <div
            id="result"
            class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"
        >
            Código detectado:
            <span id="code" class="font-black"></span>
        </div>

        <div
            id="error"
            class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
        ></div>

    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        const buscarCodigoUrl = @json(route('v2.buscar-codigo'));
        const baseUrl = @json(url('/'));

        function mostrarError(mensaje) {
            const error = document.getElementById('error');
            const result = document.getElementById('result');

            result.classList.add('hidden');
            error.textContent = mensaje;
            error.classList.remove('hidden');
        }

        function mostrarResultado(codigo) {
            const result = document.getElementById('result');
            const error = document.getElementById('error');

            error.classList.add('hidden');
            document.getElementById('code').textContent = codigo;
            result.classList.remove('hidden');
        }

        function procesarCodigo(valor) {
            const texto = String(valor || '').trim();

            if (!texto) {
                mostrarError('Ingresa o escanea un código válido.');
                return;
            }

            try {
                const url = new URL(texto);

                if (url.origin !== window.location.origin) {
                    mostrarError('El código QR pertenece a un sitio externo.');
                    return;
                }

                const rutaValida =
                    /^\/v2\/unidades\/\d+$/.test(url.pathname) ||
                    /^\/v2\/lotes\/\d+$/.test(url.pathname);

                if (!rutaValida) {
                    mostrarError('El QR no corresponde a una unidad o lote válido.');
                    return;
                }

                mostrarResultado(texto);
                window.location.href = url.href;
                return;

            } catch (error) {
                // No es una URL; se procesará como código interno.
            }

            const codigo = texto.toUpperCase();

            const esUnidad = /^INC-IND-\d{6}$/.test(codigo);
            const esLote = /^INC-LOT-\d{6}$/.test(codigo);

            if (!esUnidad && !esLote) {
                mostrarError(
                    'Formato inválido. Usa INC-IND-000001 o INC-LOT-000001.'
                );
                return;
            }

            mostrarResultado(codigo);

            window.location.href =
                buscarCodigoUrl + '?codigo=' + encodeURIComponent(codigo);
        }

        function onScanSuccess(decodedText) {
            html5QrcodeScanner
                .clear()
                .catch(() => {});

            procesarCodigo(decodedText);
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            'reader',
            {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                rememberLastUsedCamera: true
            },
            false
        );

        html5QrcodeScanner.render(
            onScanSuccess,
            () => {}
        );

        document
            .getElementById('buscarCodigo')
            .addEventListener('click', function () {
                procesarCodigo(
                    document.getElementById('codigoManual').value
                );
            });

        document
            .getElementById('codigoManual')
            .addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    procesarCodigo(event.target.value);
                }
            });
    </script>
</x-logistica-layout>