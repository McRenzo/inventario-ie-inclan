@extends('layouts.logistica')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold text-blue-600">
                Configuración
            </p>

            <h1 class="text-2xl font-bold text-slate-900">
                Parámetros
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Selecciona la opción que deseas administrar.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <a
                href="{{ route('v2.parametros.areas.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg"
            >
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V5a2 2 0 012-2h6a2 2 0 012 2v16M9 7h2m-2 4h2m-2 4h2m6-6h2m-2 4h2m-2 4h2"
                            />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-slate-900">
                            Áreas
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Crea, edita, activa o desactiva las áreas de la institución.
                        </p>

                        <span class="mt-4 inline-flex items-center text-sm font-bold text-blue-600">
                            Administrar áreas
                            <span class="ml-2 transition group-hover:translate-x-1">
                                →
                            </span>
                        </span>
                    </div>
                </div>
            </a>

            <a
                href="{{ route('v2.parametros.ubicaciones.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg"
            >
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-slate-900">
                            Ubicaciones
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Administra los espacios físicos donde se encuentran los bienes.
                        </p>

                        <span class="mt-4 inline-flex items-center text-sm font-bold text-violet-600">
                            Administrar ubicaciones
                            <span class="ml-2 transition group-hover:translate-x-1">
                                →
                            </span>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection