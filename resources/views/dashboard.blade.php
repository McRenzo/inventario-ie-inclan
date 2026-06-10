<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Panel de Control</p>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Gestión de Logística y Patrimonio') }}
                </h2>
            </div>
            <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 shadow-sm">
                📅 Periodo de Auditoría: {{ date('Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Activos</p>
                        <h3 class="text-3xl font-black text-[#001f3d] dark:text-white mt-1">2,480</h3>
                        <span class="text-[11px] text-green-500 font-bold bg-green-50 px-2 py-0.5 rounded-md mt-2 inline-block">✓ 100% Sincronizado</span>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-xl text-[#001f3d]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">En Soporte / Taller</p>
                        <h3 class="text-3xl font-black text-amber-600 mt-1">14</h3>
                        <span class="text-[11px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-md mt-2 inline-block">⚠️ Requiere revisión</span>
                    </div>
                    <div class="bg-amber-50 p-3 rounded-xl text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Propuestas de Baja</p>
                        <h3 class="text-3xl font-black text-red-600 mt-1">3</h3>
                        <span class="text-[11px] text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded-md mt-2 inline-block">❌ Pendiente de firma</span>
                    </div>
                    <div class="bg-red-50 p-3 rounded-xl text-red-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aulas / Ambientes</p>
                        <h3 class="text-3xl font-black text-blue-600 mt-1">42</h3>
                        <span class="text-[11px] text-blue-500 font-bold bg-blue-50 px-2 py-0.5 rounded-md mt-2 inline-block">🏫 Pabellones A, B y C</span>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h4 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-4">Acciones Administrativas</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('bienes.index') }}" class="flex items-center space-x-4 p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition font-semibold text-gray-700 dark:text-gray-200">
                        <div class="bg-blue-100 p-2.5 rounded-lg text-[#001f3d]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Inventario de Bienes</p>
                            <p class="text-xs font-medium text-gray-400">Ver tabla general y buscar</p>
                        </div>
                    </a>

                    <button class="flex items-center space-x-4 p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-left transition font-semibold text-gray-700 dark:text-gray-200">
                        <div class="bg-green-100 p-2.5 rounded-lg text-green-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Importación Masiva</p>
                            <p class="text-xs font-medium text-gray-400">Migrar archivos Excel (.xlsx)</p>
                        </div>
                    </button>

                    <button class="flex items-center space-x-4 p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-left transition font-semibold text-gray-700 dark:text-gray-200">
                        <div class="bg-indigo-100 p-2.5 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2M16 4h2a2 2 0 012 2v2M8 20H6a2 2 0 01-2-2v-2M8 4H6a2 2 0 00-2 2v2m4 8h8l-4-4-4 4z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Generador de Etiquetas</p>
                            <p class="text-xs font-medium text-gray-400">Imprimir códigos QR nuevos</p>
                        </div>
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Últimas actualizaciones desde las Aulas</h3>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Monitoreo en Vivo</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-50 dark:bg-gray-700 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Técnico Responsable</th>
                                <th class="px-6 py-4">Bien Patrimonial</th>
                                <th class="px-6 py-4">Ubicación Actual</th>
                                <th class="px-6 py-4">Acción Realizada</th>
                                <th class="px-6 py-4">Fecha / Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium text-gray-700 dark:text-gray-300">
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 flex items-center space-x-2">
                                    <div class="w-7 h-7 bg-slate-200 text-slate-700 font-bold rounded-full flex items-center justify-center text-xs">T1</div>
                                    <span>Téc. Soporte Campo</span>
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-semibold">Proyector Multimedia Epson</td>
                                <td class="px-6 py-4">Aula 102 - Pabellón A</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">✓ Verificado Ok</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">Hace 5 minutos</td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 flex items-center space-x-2">
                                    <div class="w-7 h-7 bg-slate-200 text-slate-700 font-bold rounded-full flex items-center justify-center text-xs">T1</div>
                                    <span>Téc. Soporte Campo</span>
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-semibold">Computadora Core i5 HP</td>
                                <td class="px-6 py-4">Laboratorio de Cómputo N° 2</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">🛠 Mantenimiento (Pantalla)</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">Hace 24 minutos</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>