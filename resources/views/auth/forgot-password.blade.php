<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Inventario Inclán</title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2zm0 0l6.586-6.586a2 2 0 012.828 0L19 19M10 12h.01M16 12h.01"/>
                    </svg>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black tracking-tight leading-[1.15] text-white">
                    Recupera el acceso a tu cuenta
                </h1>
                <p class="text-slate-300 text-base leading-relaxed font-medium opacity-90">
                    Introduce tu correo registrado y te enviaremos de inmediato un enlace seguro para restablecer tus credenciales institucionales.
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

        <!-- Panel Derecho (Formulario) -->
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
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight sm:text-4xl">¿Olvidaste tu contraseña?</h2>
                    <p class="text-sm text-slate-500 font-semibold leading-relaxed">
                        No te preocupes. Escribe tu correo electrónico y te enviaremos un enlace para restablecerla.
                    </p>
                </div>

                <!-- Estado de la Sesión / Mensaje de Éxito de Laravel -->
                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl text-xs font-semibold shadow-sm flex items-start space-x-2.5">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <!-- Cambia esto por el texto que tú quieras -->
                        <p>¡Operación exitosa! Hemos enviado un enlace de restauración a tu bandeja de entrada. Revisa tu carpeta de Spam si no lo encuentras.</p>
                    </div>
                @endif

                <!-- Alertas de Error -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-xs font-semibold space-y-1.5 shadow-sm">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p>{{ $error }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Correo electrónico -->
                    <div class="space-y-2">
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Correo electrónico</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="correo@inclan.edu.pe" 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 outline-none transition-all duration-200 shadow-inner">
                        </div>
                    </div>

                    <!-- Enlaces adicionales de navegación -->
                    <div class="text-left">
                        <a href="{{ route('login') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Regresar al inicio de sesión</span>
                        </a>
                    </div>

                    <!-- Botón de acción -->
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#001f3d] to-[#002d5a] text-white font-bold rounded-2xl shadow-lg shadow-blue-900/10 hover:shadow-xl hover:shadow-blue-900/20 hover:opacity-95 focus:ring-4 focus:ring-blue-600/10 transition duration-150 transform active:scale-[0.99] mt-3">
                        Enviar enlace de recuperación
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-slate-400 font-semibold mt-8">
                © 2026 Inventario Inclán · Uso institucional
            </p>
        </div>

    </div>
</body>
</html>