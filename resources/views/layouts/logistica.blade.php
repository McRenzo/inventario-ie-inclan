<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Inclán - Gestión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] font-sans antialiased text-gray-900 select-none">

    <div class="flex min-h-screen">
        
        <div class="w-64 bg-[#0a192f] text-gray-400 flex flex-col justify-between fixed h-full z-20 shadow-xl">
            <div>
                <div class="p-6 border-b border-white/5">
                    <h2 class="text-xl font-black text-white tracking-tight">Inventario Inclán</h2>
                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5">Sistema de Gestión</p>
                </div>

                <nav class="p-4 space-y-1.5 font-semibold text-sm">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('dashboard') ? 'bg-blue-600/10 text-blue-400 border-l-4 border-blue-500 font-bold' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('bienes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('bienes*') ? 'bg-blue-600/10 text-blue-400 border-l-4 border-blue-500 font-bold' : 'hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Inventario</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition hover:bg-white/5 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2M16 4h2a2 2 0 012 2v2M8 20H6a2 2 0 01-2-2v-2M8 4H6a2 2 0 00-2 2v2m4 8h8l-4-4-4 4z"/></svg>
                        <span>Escanear QR</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition hover:bg-white/5 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Reportes</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition hover:bg-white/5 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        <span>Análisis</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition hover:bg-white/5 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Usuarios</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition hover:bg-white/5 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Configuración</span>
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-white/5 bg-black/10 flex items-center space-x-3">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center font-bold text-white text-sm shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-500 font-medium truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline ml-auto">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-400 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 pl-64 flex flex-col min-h-screen">
            
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-10 shadow-sm">
                <div class="w-72 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Buscar activos..." class="w-full pl-9 pr-4 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-medium focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition">
                </div>
                <button class="relative p-1.5 text-gray-400 hover:text-gray-600 transition">
                    <span class="absolute top-1.5 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>
            </header>

            <main class="p-8 flex-1">
                {{ $slot }}
            </main>

            <footer class="px-8 py-4 bg-white border-t border-gray-100 flex items-center justify-between text-[11px] font-medium text-gray-400">
                <p>© 2026 inventario inclán. Todos los derechos reservados.</p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:underline">Ayuda</a>
                    <a href="#" class="hover:underline">Documentación</a>
                    <a href="#" class="hover:underline">Contacto</a>
                </div>
            </footer>
        </div>
    </div>

</body>
</html>