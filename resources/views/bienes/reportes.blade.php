<x-logistica-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-black text-slate-800">Reportes Dinámicos</h1>
            <a href="{{ route('bienes.exportar') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-green-600/20 transition-all">
                Descargar Excel
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Total Activos</p>
                <p class="text-3xl font-black text-blue-600">{{ $totalBienes }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Estado de Activos</h2>
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px]">
                        <th class="text-left pb-3">Estado</th>
                        <th class="text-right pb-3">Cantidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($bienesPorEstado as $item)
                        <tr>
                            <td class="py-3 font-medium text-slate-700">{{ $item->estado }}</td>
                            <td class="py-3 text-right font-bold text-slate-900">{{ $item->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-logistica-layout>