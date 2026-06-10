<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Inventario Inclán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex w-1/2 bg-[#001f3d] text-white p-16 flex-col justify-between relative overflow-hidden select-none">
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 filter blur-3xl"></div>
            <div class="absolute bottom-20 -right-20 w-96 h-96 bg-sky-500 rounded-full opacity-15 filter blur-3xl"></div>
            
            <div class="relative z-10 flex items-center space-x-3.5">
                <div class="bg-white/10 p-2.5 rounded-xl backdrop-blur-md border border-white/10 shadow-inner">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-blue-300/80 leading-none">Sistema de</p>
                    <h2 class="text-xl font-extrabold tracking-tight mt-0.5">Inventario Inclán</h2>
                </div>
            </div>

            <div class="relative z-10 my-auto max-w-md space-y-6">
                <div class="inline-flex bg-white/10 p-2.5 rounded-xl backdrop-blur-md border border-white/10 shadow-sm">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black tracking-tight leading-[1.1] text-white">
                    Gestión de inventario patrimonial
                </h1>
                <p class="text-gray-300/90 text-base leading-relaxed font-normal">
                    Controla, rastrea y administra el inventario institucional en tiempo real. Acceso seguro para administradores y personal autorizado.
                </p>
            </div>

            <div class="relative z-10 grid grid-cols-3 gap-4">
                <div class="bg-white/[0.04] border border-white/10 backdrop-blur-md p-4 rounded-2xl shadow-sm transition hover:bg-white/[0.07]">
                    <p class="text-2xl font-black tracking-tight text-white">2,400+</p>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Activos registrados</p>
                </div>
                <div class="bg-white/[0.04] border border-white/10 backdrop-blur-md p-4 rounded-2xl shadow-sm transition hover:bg-white/[0.07]">
                    <p class="text-2xl font-black tracking-tight text-white">12</p>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Áreas cubiertas</p>
                </div>
                <div class="bg-white/[0.04] border border-white/10 backdrop-blur-md p-4 rounded-2xl shadow-sm transition hover:bg-white/[0.07]">
                    <p class="text-2xl font-black tracking-tight text-white">99%</p>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Disponibilidad</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-between items-center p-8 lg:p-16 bg-white">
            <div class="my-auto w-full max-w-md space-y-7">
                
                <div class="text-center lg:text-left space-y-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">Bienvenido de vuelta</h2>
                    <p class="text-sm text-gray-500 font-medium">Inicia sesión para acceder al sistema institucional.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-sm space-y-1.5 shadow-sm animate-fade-in">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center space-x-2">
                                <span class="text-base">⚠️</span>
                                <p class="font-medium">{{ $error }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Correo electrónico</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-[#001f3d] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="correo@inclan.edu.pe" 
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-[#001f3d] focus:border-transparent outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-[#001f3d] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" type="password" name="password" required placeholder="••••••••" 
                                class="w-full pl-11 pr-11 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-[#001f3d] focus:border-transparent outline-none transition shadow-sm">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <div class="text-right mt-2">
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#001f3d] hover:text-blue-800 transition-colors">¿Olvidaste tu contraseña?</a>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-[#001f3d] text-white font-bold rounded-2xl shadow-md hover:bg-slate-800 focus:ring-4 focus:ring-blue-900/20 transition transform active:scale-[0.99] mt-2">
                        Iniciar sesión
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 font-medium mt-8">
                © 2026 Inventario Inclán · Uso institucional
            </p>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>
</html>