<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Inventario Inclán</title>
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
                    Gestión de inventario patrimonial
                </h1>
                <p class="text-slate-300 text-base leading-relaxed font-medium opacity-90">
                    Controla, rastrea y administra el inventario institucional en tiempo real. Acceso seguro para administradores y personal autorizado.
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

        <div class="w-full lg:w-1/2 flex flex-col justify-between items-center p-8 lg:p-16 bg-white">
            
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
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight sm:text-4xl">Bienvenido de vuelta</h2>
                    <p class="text-sm text-slate-500 font-semibold">Inicia sesión para acceder al sistema institucional.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-xs font-semibold space-y-1.5 shadow-sm">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <p>{{ $error }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Correo electrónico</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="correo@inclan.edu.pe" 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 outline-none transition-all duration-200 shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" type="password" name="password" required placeholder="••••••••" 
                                class="w-full pl-12 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 outline-none transition-all duration-200 shadow-inner">
                            
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 transition-colors" id="toggle-btn">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <div class="text-right">
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">¿Olvidaste tu contraseña?</a>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#001f3d] to-[#002d5a] text-white font-bold rounded-2xl shadow-lg shadow-blue-900/10 hover:shadow-xl hover:shadow-blue-900/20 hover:opacity-95 focus:ring-4 focus:ring-blue-600/10 transition duration-150 transform active:scale-[0.99] mt-3">
                        Iniciar sesión
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-slate-400 font-semibold mt-8">
                © 2026 Inventario Inclán · Uso institucional
            </p>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>