<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | Inventario Inclán</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

    <main class="min-h-screen lg:grid lg:grid-cols-[1.05fr_0.95fr]">

        {{-- Panel institucional --}}
        <section
            class="relative hidden overflow-hidden bg-[#062b4f] px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-16"
        >
            <div class="absolute inset-0">
                <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-sky-400/10 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-blue-300/10 blur-3xl"></div>

                <div
                    class="absolute inset-0 opacity-[0.035]"
                    style="
                        background-image:
                            linear-gradient(rgba(255,255,255,.9) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255,255,255,.9) 1px, transparent 1px);
                        background-size: 42px 42px;
                    "
                ></div>
            </div>

            <div class="relative z-10 flex items-center gap-4">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/5 p-1.5 shadow-lg backdrop-blur-sm"
                >
                    <img
                        src="{{ asset('images/insignia-inclan.png') }}"
                        alt="Insignia de la IE José Joaquín Inclán"
                        class="h-full w-full object-contain"
                    >
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-300">
                        IE José Joaquín Inclán
                    </p>

                    <h1 class="mt-1 text-xl font-extrabold tracking-tight">
                        Inventario Patrimonial
                    </h1>
                </div>
            </div>

            <div class="relative z-10 max-w-xl">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-sky-300/20 bg-sky-300/10 px-4 py-2 text-xs font-semibold text-sky-200"
                >
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Creación de cuenta
                </span>

                <h2 class="mt-7 text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">
                    Acceso para personal autorizado
                </h2>

                <p class="mt-5 max-w-lg text-base leading-7 text-slate-300">
                    Registra tu cuenta para acceder al sistema institucional.
                    Será necesario confirmar el correo electrónico antes de usarla.
                </p>

                <div class="mt-10 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                    <div class="flex gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-300/10">
                            <svg
                                class="h-5 w-5 text-sky-300"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-white">
                                Verificación obligatoria
                            </p>

                            <p class="mt-1 text-xs leading-5 text-slate-400">
                                Recibirás un enlace en tu correo para activar la cuenta.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="relative z-10 text-xs text-slate-400">
                © {{ date('Y') }} IE José Joaquín Inclán
            </p>
        </section>

        {{-- Formulario --}}
        <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
            <div class="w-full max-w-md">

                <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-white p-1 shadow-lg"
                    >
                        <img
                            src="{{ asset('images/insignia-inclan.png') }}"
                            alt="Insignia de la IE José Joaquín Inclán"
                            class="h-full w-full object-contain"
                        >
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
                            IE José Joaquín Inclán
                        </p>

                        <p class="font-extrabold text-slate-900">
                            Inventario Patrimonial
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-300/30 sm:p-9">

                    <div class="mb-8">
                        <p class="text-sm font-semibold text-sky-700">
                            Registro de usuario
                        </p>

                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">
                            Crear cuenta
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Completa tus datos personales y confirma tu correo.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div
                            class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                        >
                            <div class="flex gap-3">
                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20h15.6a2 2 0 001.73-3l-7.8-13.5a2 2 0 00-3.44 0z"
                                    />
                                </svg>

                                <div class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Nombre completo
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Ingresa tu nombre completo"
                                class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-4 focus:ring-sky-100"
                            >
                        </div>

                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Correo electrónico
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                placeholder="correo@institucion.edu.pe"
                                class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-4 focus:ring-sky-100"
                            >
                        </div>

                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Contraseña
                            </label>

                            <div class="relative">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Crea una contraseña segura"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-12 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                >
                                
                                <button
                                type="button"
                                data-toggle-password="password"
                                aria-label="Mostrar contraseña"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-700"
                                >
                                <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                >
                                <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.46 12C3.73 7.94 7.52 5 12 5s8.27 2.94 9.54 7c-1.27 4.06-5.06 7-9.54 7s-8.27-2.94-9.54-7z"
                                />
                            </svg>
                        </button>
                    </div>
                        <p class="mt-2 text-xs text-slate-500">
                            Debe contener como mínimo 8 caracteres.
                        </p>
                </div>

                        <div>
                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Confirmar contraseña
                            </label>

                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Repite la contraseña"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-12 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                >

                                <button
                                    type="button"
                                    data-toggle-password="password_confirmation"
                                    aria-label="Mostrar contraseña"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-700"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.46 12C3.73 7.94 7.52 5 12 5s8.27 2.94 9.54 7c-1.27 4.06-5.06 7-9.54 7s-8.27-2.94-9.54-7z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#062b4f] px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-900/15 transition hover:bg-[#0a3a68] focus:outline-none focus:ring-4 focus:ring-sky-200 active:scale-[0.99]"
                        >
                            Crear cuenta
                        </button>
                    </form>

                    <div class="mt-7 border-t border-slate-200 pt-5">
                        <p class="text-center text-sm text-slate-500">
                            ¿Ya tienes una cuenta?

                            <a
                                href="{{ route('login') }}"
                                class="font-semibold text-sky-700 hover:text-sky-900"
                            >
                                Inicia sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const inputId = button.getAttribute('data-toggle-password');
                    const input = document.getElementById(inputId);

                    if (!input) {
                        return;
                    }

                    const isHidden = input.type === 'password';

                    input.type = isHidden ? 'text' : 'password';

                    button.setAttribute(
                        'aria-label',
                        isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña'
                    );
                });
            });
        });
    </script>
</body>
</html>