<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo - Inventario Inclán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased text-slate-900 select-none">

    <div class="flex min-h-screen">
        
        <!-- Panel Izquierdo (Diseño institucional) -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-b from-[#00172e] to-[#00284f] text-white p-16 flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-20 -left-20 w-[450px] h-[450px] bg-blue-600 rounded-full opacity-20 filter blur-[100px]"></div>
            <div class="absolute bottom-20 -right-20 w-[450px] h-[450px] bg-sky-500 rounded-full opacity-15 filter blur-[100px]"></div>
            
            <div class="relative z-10 flex items-center space-x-3.5">
                <div class="bg-white/10 p-2.5 rounded-2xl backdrop-blur-md border border-white/10 shadow-inner">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400 leading-none">Sistema de</p>
                    <h2 class="text-xl font-black tracking-tight mt-1">Inventario Inclán</h2>
                </div>
            </div>

            <div class="relative z-10 my-auto max-w-md space-y-6">
                <div class="inline-flex bg-blue-500/10 p-3 rounded-2xl backdrop-blur-md border border-blue-500/20 shadow-sm">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black tracking-tight leading-[1.15] text-white">
                    Valida tus accesos institucionales
                </h1>
                <p class="text-slate-300 text-base leading-relaxed font-medium opacity-90">
                    Por motivos de seguridad y resguardo del patrimonio del Estado, requerimos que confirmes tu cuenta antes de acceder al entorno operativo global.
                </p>
            </div>

            <div class="relative z-10 grid grid-cols-3 gap-4">
                <div class="bg-white/[0.03] border border-white/10 backdrop-blur-lg p-4 rounded-2xl shadow-[px_4px_12px_rgba(0,0,0,0.1)] transition-colors hover:bg-white/[0.06]">
                    <p class="text-2xl font-black tracking-tight text-white">2,400+</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Activos</p>
                </div>
                <div class="bg-white/[0.03] border border-white/10 backdrop-blur-lg p-4 rounded-2xl shadow-[px_4px_12px_rgba(0,0,0,0.1)] transition-colors hover:bg-white/[0.06]">
                    <p class="text-2xl font-black tracking-tight text-white">12</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Áreas</p>
                </div>
                <div class="bg-white/[0.03] border border-white/10 backdrop-blur-lg p-4 rounded-2xl shadow-[px_4px_12px_rgba(0,0,0,0.1)] transition-colors hover:bg-white/[0.06]">
                    <p class="text-2xl font-black tracking-tight text-white">99%</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Uptime</p>
                </div>
            </div>
        </div>

        <!-- Panel Derecho (Formulario de Verificación) -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between items-center p-8 lg:p-16 bg-white">
            
            <!-- Header Móvil -->
            <div class="w-full flex justify-center lg:hidden mt-4">
                <div class="flex items-center space-x-3 bg-slate-100 p-3 rounded-2xl border border-slate-200/60 shadow-sm">
                    <svg class="w-6 h-6 text-[#001f3d]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-black text-slate-900 tracking-tight uppercase">Inventario Inclán</span>
                </div>
            </div>

            <div class="my-auto w-full max-w-md space-y-7">
                
                <div class="text-center lg:text-left space-y-2">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight sm:text-4xl">Verifica tu correo</h2>
                    <p class="text-sm text-slate-500 font-semibold leading-relaxed">
                        ¡Gracias por registrarte! Antes de comenzar, por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarte. Si no lo recibiste, con gusto te enviaremos otro.
                    </p>
                </div>

                <!-- Mensaje de Enlace Enviado (Verde Esmeralda Premium) -->
                @if (session('status') == 'verification-link-sent')
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl text-xs font-semibold shadow-sm flex items-start space-x-2.5 animate-fadeIn">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Un nuevo enlace de verificación ha sido enviado a la dirección de correo electrónico proporcionada durante el registro.</p>
                    </div>
                @endif

                <!-- Contenedor de Acciones -->
                <div class="space-y-4 pt-2">
                    <!-- Formulario: Reenviar Verificación -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#001f3d] to-[#002d5a] text-white font-bold rounded-2xl shadow-lg shadow-blue-900/10 hover:shadow-xl hover:shadow-blue-900/20 hover:opacity-95 focus:ring-4 focus:ring-blue-600/10 transition duration-150 transform active:scale-[0.99]">
                            Reenviar correo de verificación
                        </button>
                    </form>

                    <!-- Formulario: Cerrar Sesión -->
                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-slate-400 hover:text-slate-600 tracking-wide uppercase transition-colors outline-none focus:underline">
                            Cerrar sesión actual
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 font-semibold mt-8">
                © 2026 Inventario Inclán · Uso institucional
            </p>
        </div>

    </div>
</body>
</html>