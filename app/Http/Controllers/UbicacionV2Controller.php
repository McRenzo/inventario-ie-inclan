<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UbicacionV2Controller extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim($request->string('buscar')->toString());

        $ubicaciones = Ubicacion::query()
            ->withCount([
                'unidades',
                'lotes',
            ])
            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {
                    $query->where(function ($subquery) use ($buscar) {
                        $subquery
                            ->where(
                                'nombre',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'codigo',
                                'like',
                                "%{$buscar}%"
                            );
                    });
                }
            )
            ->orderByDesc('activo')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view(
            'v2.parametros.ubicaciones.index',
            compact('ubicaciones', 'buscar')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:ubicaciones,nombre',
            ],

            'codigo' => [
                'nullable',
                'string',
                'max:50',
                'unique:ubicaciones,codigo',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        Ubicacion::create([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'] ?? null,
            'descripcion' => $datos['descripcion'] ?? null,
            'area_id' => null,
            'activo' => true,
        ]);

        return redirect()
            ->route('v2.parametros.ubicaciones.index')
            ->with(
                'success',
                'La ubicación fue creada correctamente.'
            );
    }

    public function update(
        Request $request,
        Ubicacion $ubicacion
    ): RedirectResponse {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'ubicaciones',
                    'nombre'
                )->ignore($ubicacion->id),
            ],

            'codigo' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(
                    'ubicaciones',
                    'codigo'
                )->ignore($ubicacion->id),
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $ubicacion->update([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'] ?? null,
            'descripcion' => $datos['descripcion'] ?? null,
            'area_id' => null,
        ]);

        return redirect()
            ->route('v2.parametros.ubicaciones.index')
            ->with(
                'success',
                'La ubicación fue actualizada correctamente.'
            );
    }

    public function cambiarEstado(
        Ubicacion $ubicacion
    ): RedirectResponse {
        $ubicacion->update([
            'activo' => !$ubicacion->activo,
        ]);

        $mensaje = $ubicacion->activo
            ? 'La ubicación fue activada correctamente.'
            : 'La ubicación fue desactivada correctamente.';

        return redirect()
            ->route('v2.parametros.ubicaciones.index')
            ->with('success', $mensaje);
    }
}