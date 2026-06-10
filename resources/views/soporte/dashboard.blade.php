<x-logistica-layout>
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard</h1>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Resumen general del inventario institucional</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">Total de Activos</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">1,248</h3>
                <span class="text-[11px] text-green-500 font-bold mt-1.5 inline-block">+12% vs mes anterior</span>
            </div>
            <div class="bg-gray-100 p-2.5 rounded-xl text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">Activos Activos</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">1,185</h3>
                <span class="text-[11px] text-green-500 font-bold mt-1.5 inline-block">+8% vs mes anterior</span>
            </div>
            <div class="bg-gray-100 p-2.5 rounded-xl text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">En Mantenimiento</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">42</h3>
                <span class="text-[11px] text-red-500 font-bold mt-1.5 inline-block">-3% vs mes anterior</span>
            </div>
            <div class="bg-gray-100 p-2.5 rounded-xl text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 tracking-wide">Valor Total</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">$2.4M</h3>
                <span class="text-[11px] text-green-500 font-bold mt-1.5 inline-block">+15% vs mes anterior</span>
            </div>
            <div class="bg-gray-100 p-2.5 rounded-xl text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <h4 class="text-sm font-extrabold text-gray-900 tracking-tight mb-5">Categorías de Activos</h4>
            
            <div class="space-y-4 flex-1 flex flex-col justify-around">
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-gray-700">Equipos de Cómputo</span>
                        <span class="text-gray-400">456</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full">
                        <div class="bg-[#0a192f] h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-gray-700">Mobiliario</span>
                        <span class="text-gray-400">342</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full">
                        <div class="bg-[#0a192f] h-2 rounded-full" style="width: 55%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-gray-700">Equipos Audiovisuales</span>
                        <span class="text-gray-400">234</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full">
                        <div class="bg-[#0a192f] h-2 rounded-full" style="width: 40%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-gray-700">Material Deportivo</span>
                        <span class="text-gray-400">156</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full">
                        <div class="bg-[#0a192f] h-2 rounded-full" style="width: 25%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-gray-700">Otros</span>
                        <span class="text-gray-400">60</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full">
                        <div class="bg-[#0a192f] h-2 rounded-full" style="width: 12%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="text-sm font-extrabold text-gray-900 tracking-tight mb-5">Actividad Reciente</h4>
            
            <div class="relative border-l border-gray-100 pl-4 space-y-5">
                <div class="relative">
                    <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 bg-green-500 rounded-full ring-4 ring-white"></span>
                    <p class="text-xs font-bold text-gray-800">Nuevo activo registrado</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Laptop Dell XPS 15</p>
                    <span class="text-[10px] text-gray-400 font-bold block mt-0.5">Hace 2 horas</span>
                </div>

                <div class="relative">
                    <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 bg-amber-500 rounded-full ring-4 ring-white"></span>
                    <p class="text-xs font-bold text-gray-800">Mantenimiento programado</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Proyector Sala 301</p>
                    <span class="text-[10px] text-gray-400 font-bold block mt-0.5">Hace 4 horas</span>
                </div>

                <div class="relative">
                    <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 bg-red-500 rounded-full ring-4 ring-white"></span>
                    <p class="text-xs font-bold text-gray-800">Activo dado de baja</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Escritorio OF-234</p>
                    <span class="text-[10px] text-gray-400 font-bold block mt-0.5">Hace 1 día</span>
                </div>

                <div class="relative">
                    <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 bg-green-500 rounded-full ring-4 ring-white"></span>
                    <p class="text-xs font-bold text-gray-800">Transferencia de activo</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Silla Ergonómica SE-102</p>
                    <span class="text-[10px] text-gray-400 font-bold block mt-0.5">Hace 2 días</span>
                </div>
            </div>
        </div>

    </div>

</x-logistica-layout>