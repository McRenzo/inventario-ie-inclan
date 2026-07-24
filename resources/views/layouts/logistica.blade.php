<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Inclán - Panel de Gestión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* CORREGIDO: !important en lugar de !make-important */
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#f4f7fa] antialiased text-slate-800 select-none">

    <div class="flex min-h-screen p-0 lg:p-4 gap-4">

        <aside
            class="hidden lg:flex w-72 bg-white/80 text-slate-600 flex-col justify-between fixed top-4 left-4 h-[calc(100vh-2rem)] z-20 rounded-3xl shadow-[0_10px_30px_-10px_rgba(0,30,80,0.05)] border border-slate-200/60 backdrop-blur-xl">
            <div>
                <div class="p-6 flex items-center space-x-3.5 border-b border-slate-100 bg-slate-50/50 rounded-t-3xl">
                    <div
                        class="bg-gradient-to-tr from-blue-600 to-blue-500 p-2.5 rounded-2xl shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight leading-none">Inclán</h2>
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-blue-600/10 text-blue-600 border border-blue-500/10 uppercase tracking-widest mt-1.5">Sistema
                            QR</span>
                    </div>
                </div>

                <nav class="px-4 py-4 space-y-6 font-medium text-xs">
                    <div class="space-y-1">
                        <p class="px-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Monitoreo
                        </p>

                        <a href="{{ route('dashboard') }}"
                            class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Request::is('dashboard') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-105" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>Dashboard</span>
                            </div>
                        </a>

                        <a href="{{ route('v2.bienes.index') }}"
                            class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                            {{ request()->routeIs('v2.bienes.*', 'v2.unidades.*', 'v2.lotes.*', 'v2.transferencias.*')
                                ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20'
                                : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-105" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>

                                <span>Inventario</span>
                            </div>
                        </a>

                        <a href="{{ route('v2.prestamos.index') }}"
                            class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                                {{ request()->routeIs('v2.prestamos.*')
                                    ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20'
                                    : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-105"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V5a4 4 0 0 1 8 0v2M5 7h14l-1 13H6L5 7Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6" />
                                </svg>

                                <span>Préstamos</span>
                            </div>
                        </a>
                    </div>

                    <div class="space-y-1">
                        <p class="px-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Operaciones
                        </p>

                        <a href="{{ route('bienes.escanear') }}"
                            class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Request::is('bienes/escanear') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20' : 'bg-blue-600/10 text-blue-600 border border-blue-500/5 hover:bg-blue-600/15' }}">
                            <span class="relative flex h-2 w-2 mr-0.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2M16 4h2a2 2 0 012 2v2M8 20H6a2 2 0 01-2-2v-2M8 4H6a2 2 0 00-2 2v2m4 8h8l-4-4-4 4z" />
                            </svg>
                            <span class="font-bold">Escanear Activo QR</span>
                        </a>

                    </div>

                    <div class="space-y-1">
                        <p class="px-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ajustes</p>

                        <a href="{{ route('v2.parametros.index') }}"
                            class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                {{ request()->routeIs('v2.parametros.*')
                                    ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20'
                                    : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 transition-transform group-hover:scale-105" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <span>Parámetros</span>
                        </a>
                    </div>
                </nav>
            </div>

            <div
                class="p-3 m-3 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between shadow-inner">
                <div class="flex items-center space-x-2.5 overflow-hidden">
                    <div
                        class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-black text-white text-xs shadow-md shadow-blue-600/10 shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden leading-tight">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 font-medium truncate mt-0.5">{{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline shrink-0">
                    @csrf
                    <button type="submit"
                        class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition border border-transparent"
                        title="Cerrar sesión">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 lg:ml-[19rem] flex flex-col min-h-screen">

            <header
                class="h-16 bg-white/70 border border-slate-200/60 flex items-center justify-between px-6 rounded-2xl sticky top-0 lg:top-4 z-10 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.02)] backdrop-blur-md">
                <div class="w-96 relative group">
                </div>

                <div class="flex items-center space-x-2">
                    <span class="text-xs font-semibold text-slate-500">
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </header>

            <main class="py-6 pb-24 flex-1">
                <div class="animate-fade-in">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>
            </main>

            <footer
                class="sticky bottom-0 lg:bottom-4 z-10 bg-white/70 border border-slate-200/60 backdrop-blur-md px-6 py-4 rounded-2xl shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.02)] flex flex-col sm:flex-row items-center justify-between text-[11px] font-semibold text-slate-400 tracking-wide gap-2">
                <p>© 2026 Inventario Inclán. Panel de Control y Activos Institucionales.</p>
                <div class="flex items-center space-x-2 text-slate-400">
                    <span>Versión 1.0.0</span>
                </div>
            </footer>
        </div>
    </div>

</body>

</html>
