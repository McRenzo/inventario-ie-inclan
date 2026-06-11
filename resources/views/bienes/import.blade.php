<x-logistica-layout>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="mb-6">
            <a href="{{ route('bienes.index') }}" class="text-xs font-bold text-gray-400 hover:text-blue-600 transition flex items-center space-x-1 mb-2">
                <span>← Volver al Inventario</span>
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Migración de Inventario Histórico</h1>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Herramienta de carga única para consolidar los archivos Excel del colegio.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-xs font-semibold mb-6 shadow-sm">
                @foreach ($errors->all() as $error)
                    <p>❌ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('bienes.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Clasificación del Archivo a Subir</label>
                    <select name="categoria_nombre" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 outline-none transition font-medium text-gray-700">
                        <option value="" disabled selected>Selecciona a qué área pertenece este Excel...</option>
                        <option value="COMUNICACIONES">Comunicaciones</option>
                        <option value="INGENIERIA">Ingeniería</option>
                        <option value="INTENDENCIA">Intendencia</option>
                        <option value="SANIDAD">Sanidad</option>
                    </select>
                </div>

                <div class="border-2 border-dashed border-gray-200 hover:border-blue-500 rounded-2xl p-8 text-center transition bg-gray-50/50 relative group">
                    <input type="file" name="excel_file" required accept=".xls,.xlsx,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('nombre_archivo').innerText = '✅ Archivo cargado: ' + this.files[0].name; document.getElementById('nombre_archivo').classList.add('text-green-600');">
                    
                    <svg class="w-12 h-12 text-gray-400 group-hover:text-blue-500 mx-auto mb-3 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/></svg>
                    
                    <p id="nombre_archivo" class="text-sm font-bold text-gray-700 transition-colors">Selecciona o arrastra el archivo ORIGINAL aquí</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-1">Soporta los archivos crudos del colegio (.xls, .xlsx)</p>
                </div>

                <div class="bg-blue-50/40 border border-blue-100 p-4 rounded-xl text-xs text-slate-700 font-medium leading-relaxed">
                    💡 <strong>Nota del Motor de Procesamiento:</strong> El sistema está configurado para leer directamente las columnas C (Descripción), D (Cantidad), I (Procedencia), J (Estado), K (Fecha) y L (Observaciones/Ubicación) de tus actas originales e ignorar automáticamente las cabeceras institucionales.
                </div>

                <button type="submit" class="w-full py-3.5 bg-[#0a192f] hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition transform active:scale-[0.99]">
                    ⚡ Procesar Archivo Original
                </button>
            </form>
        </div>
    </div>

</x-logistica-layout>