<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Módulo Operativo</p>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Panel de Soporte y Verificación') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-6 flex flex-col justify-between border border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Auditoría con QR / Barras</h3>
                        <p class="text-sm text-gray-500 mb-4">Activa la cámara del dispositivo para identificar el activo in situ de forma inmediata.</p>
                    </div>
                    
                    <div class="border-2 border-dashed border-indigo-500 rounded-xl p-6 bg-gray-50 dark:bg-gray-900 text-center mb-4">
                        <svg class="w-12 h-12 text-indigo-500 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2M16 4h2a2 2 0 012 2v2M8 20H6a2 2 0 01-2-2v-2M8 4H6a2 2 0 00-2 2v2m4 8h8l-4-4-4 4z"/>
                        </svg>
                        <span class="text-xs text-gray-400 block md:hidden">Listo para escanear con la cámara del celular</span>
                        <span class="text-xs text-gray-400 hidden md:block">Modo PC: Entrada manual disponible</span>
                    </div>

                    <button class="w-full bg-[#001f3d] hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow transition">
                        📷 Encender Escáner
                    </button>
                </div>

                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Mis Actividades Recientes</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3">Código</th>
                                    <th class="px-4 py-3">Bien</th>
                                    <th class="px-4 py-3">Ubicación</th>
                                    <th class="px-4 py-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="font-medium text-gray-700 dark:text-gray-300">
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 text-gray-900 dark:text-white font-bold">QR-00412</td>
                                    <td class="px-4 py-3">Proyector Multimedia Epson</td>
                                    <td class="px-4 py-3">AULA 204 - Pabellón B</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Verificado</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</x-app-layout>