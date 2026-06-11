<x-logistica-layout>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">Total de Activos</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $data['totalActivos'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">Activos Disponibles</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $data['activosDisponibles'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">En Mantenimiento</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $data['enMantenimiento'] }}</h3>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-extrabold text-gray-900 tracking-tight mb-5">Categorías de Activos</h4>
        <div class="space-y-4">
            @foreach($data['categoriasStats'] as $cat)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-gray-700">{{ $cat->nombre }}</span>
                        <span class="text-gray-400">{{ $cat->bienes_count }}</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full">
                        <div class="bg-[#0a192f] h-2 rounded-full" 
                             style="width: {{ ($data['totalActivos'] > 0) ? ($cat->bienes_count / $data['totalActivos'] * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-logistica-layout>