<x-logistica-layout>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-xl font-black text-slate-800 mb-4">Escanear Activo</h1>
        
        <div id="reader" class="w-full border-2 border-gray-300 rounded-2xl overflow-hidden"></div>

        <div id="result" class="mt-4 p-4 bg-green-50 text-green-700 font-bold rounded-xl hidden">
            Código detectado: <span id="code"></span>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Detener el escáner al detectar
            html5QrcodeScanner.clear();
            
            // Mostrar resultado
            document.getElementById('result').classList.remove('hidden');
            document.getElementById('code').innerText = decodedText;
            
            // Redirigir al buscador del inventario
            window.location.href = "{{ route('bienes.index') }}?search=" + decodedText;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-logistica-layout>