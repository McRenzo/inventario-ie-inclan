<x-logistica-layout>
<div x-data="{ open: {{ isset($bienEditar) ? 'true' : 'false' }}, step: 1, openDelete: false, bienId: null, bienCodigo: '', bienNombre: '' }">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Inventario de Bienes</h1>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Gestión centralizada del patrimonio institucional</p>
        </div>
        <button type="button" @click="open = true" class="bg-[#0a192f] hover:bg-slate-800 text-white text-xs font-bold px-4 py-3 rounded-xl shadow-md transition transform active:scale-[0.98]">
            + Registrar Nuevo Bien
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <div class="px-6 py-4 border-b border-gray-100">
                <form action="{{ route('bienes.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Buscar por nombre o código..." 
                        class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs w-full max-w-sm">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold">Filtrar</button>
                    @if(request('search'))
                        <a href="{{ route('bienes.index') }}" class="px-4 py-2 text-xs text-red-500 font-bold underline">Limpiar</a>
                    @endif
                </form>
            </div>
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50/80 font-bold tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Código</th>
                        <th class="px-6 py-4">Nombre / Bien</th>
                        <th class="px-6 py-4">Categoría</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4">Estado Conserv.</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse ($bienes as $bien)
                        <tr class="hover:bg-gray-50/40 transition">
                            <td class="px-6 py-4 font-bold text-[#0a192f] font-mono text-xs">{{ $bien->codigo_barras_qr }}</td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-semibold">{{ $bien->nombre }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $bien->numero_serie ?? 'S/N' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $bien->categoria->nombre }}</td>
                            <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 text-[11px] px-2.5 py-1 rounded-md">{{ $bien->ubicacion->nombre }}</span></td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">{{ $bien->estado->nombre }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('bienes.edit', $bien->id) }}?page={{ request()->get('page', 1) }}&search={{ request()->get('search') }}" class="text-gray-500 hover:text-blue-600 transition" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button type="button" @click="openDelete = true; bienId = {{ $bien->id }}; bienCodigo = '{{ $bien->codigo_barras_qr }}'; bienNombre = '{{ $bien->nombre }}'" class="text-gray-500 hover:text-red-600 transition" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-gray-400 text-sm">No hay bienes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-100">{{ $bienes->links() }}</div>
    </div>

    <div x-show="open" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[50] flex items-center justify-center p-4" x-cloak>
        <div @click.away="open = false" class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden transition-all transform">
            <form action="{{ isset($bienEditar) ? route('bienes.update', $bienEditar->id) : route('bienes.store') }}" method="POST">
                @csrf @if(isset($bienEditar)) @method('PUT') @endif
                <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-[#0a192f] p-3 rounded-2xl text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="{{ isset($bienEditar) ? 'M15 3l6 6-9 9H3v-6l9-9z' : 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' }}"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-800">{{ isset($bienEditar) ? 'Editar activo' : 'Nuevo Activo' }}</h2>
                            <p class="text-[11px] font-bold text-gray-400 uppercase">{{ isset($bienEditar) ? $bienEditar->codigo_barras_qr . ' · ' . $bienEditar->nombre : 'Generando nuevo código...' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('bienes.index', ['page' => request()->get('page', 1)]) }}" class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></a>
                </div>

                <div class="p-8 space-y-5 text-xs">
                    <div x-show="step === 1" class="space-y-4">
                        <div><label class="block font-bold text-gray-700 uppercase mb-1.5">Nombre del Bien *</label><input type="text" name="nombre" value="{{ $bienEditar->nombre ?? '' }}" class="w-full p-3 bg-gray-50 border rounded-xl" required></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block font-bold text-gray-700 uppercase mb-1.5">N° de Serie</label><input type="text" name="numero_serie" value="{{ $bienEditar->numero_serie ?? '' }}" class="w-full p-3 bg-gray-50 border rounded-xl"></div>
                            <div><label class="block font-bold text-gray-700 uppercase mb-1.5">Fecha Ingreso</label><input type="date" name="fecha_ingreso_origen" value="{{ $bienEditar->fecha_ingreso_origen ?? '' }}" class="w-full p-3 bg-gray-50 border rounded-xl"></div>
                        </div>
                        <div><label class="block font-bold text-gray-700 uppercase mb-1.5">Descripción</label><textarea name="descripcion" class="w-full p-3 bg-gray-50 border rounded-xl">{{ $bienEditar->descripcion ?? '' }}</textarea></div>
                    </div>
                    <div x-show="step === 2" class="space-y-4">
                        <div><label class="block font-bold text-gray-700 mb-1">Categoría *</label><select name="categoria_id" class="w-full p-3 bg-gray-50 border rounded-xl"> @foreach($categorias as $cat) <option value="{{ $cat->id }}" {{ (isset($bienEditar) && $bienEditar->categoria_id == $cat->id) ? 'selected' : '' }}>{{ $cat->nombre }}</option> @endforeach </select></div>
                        <div><label class="block font-bold text-gray-700 mb-1">Procedencia</label><input type="text" name="procedencia" value="{{ $bienEditar->procedencia ?? '' }}" class="w-full p-3 bg-gray-50 border rounded-xl"></div>
                        <div><label class="block font-bold text-gray-700 mb-1">Ubicación *</label><input type="text" name="ubicacion_nombre" value="{{ $bienEditar->ubicacion->nombre ?? '' }}" class="w-full p-3 bg-gray-50 border rounded-xl" required></div>
                        <div><label class="block font-bold text-gray-700 mb-1">Estado *</label><input type="text" name="estado_nombre" value="{{ $bienEditar->estado->nombre ?? '' }}" class="w-full p-3 bg-gray-50 border rounded-xl" required></div>
                    </div>
                </div>

                <div class="px-8 py-5 border-t border-gray-100 flex justify-between bg-gray-50/50">
                    <button type="button" x-show="step > 1" @click="step--" class="text-xs font-bold text-gray-500">Atrás</button>
                    <div class="flex-grow"></div>
                    <button type="button" x-show="step < 2" @click="step++" class="px-6 py-2 bg-[#0a192f] text-white rounded-xl text-xs font-bold">Siguiente ➔</button>
                    <button type="submit" x-show="step === 2" class="px-6 py-2 bg-green-600 text-white rounded-xl text-xs font-bold">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openDelete" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4" x-cloak>
        <div @click.away="openDelete = false" class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl relative">
            
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-red-50 p-2 rounded-full text-red-600 border border-red-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h2 class="text-lg font-black text-slate-800">Eliminar activo</h2>
                <button @click="openDelete = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 mb-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <p class="text-xs font-bold text-red-700">Esta acción es permanente e irreversible.</p>
                        <p class="text-[11px] text-red-600/80 mt-1">El activo y todo su historial serán eliminados del sistema. No podrás recuperar esta información.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100/50 rounded-2xl p-4 mb-6 flex items-center gap-4 border border-gray-200/50">
                <div class="bg-red-100 p-2.5 rounded-xl text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800" x-text="bienNombre"></p>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wide" x-text="bienCodigo"></p>
                </div>
            </div>

            <form :action="'/bienes/' + bienId" method="POST">
                @csrf @method('DELETE')
                <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                
                <label class="text-xs font-bold text-slate-700 block mb-2">
                    Para confirmar, escribe el código del activo: <span class="text-red-600" x-text="bienCodigo"></span>
                </label>
                <input type="text" name="codigo_confirmacion" 
                       :placeholder="bienCodigo"
                       class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 outline-none transition" 
                       required>
                
                <div class="flex gap-3 mt-6 border-t pt-6">
                    <button type="button" @click="openDelete = false" 
                            class="flex-1 px-5 py-3 text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Cancelar</button>
                    <button type="submit" 
                            class="flex-1 px-5 py-3 bg-gray-800 hover:bg-red-600 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Eliminar definitivamente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-logistica-layout>