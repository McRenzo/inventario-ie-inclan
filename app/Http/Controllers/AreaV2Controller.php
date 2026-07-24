<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AreaV2Controller extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim($request->string('buscar')->toString());

        $areas = Area::query()
            ->withCount([
                'unidades',
                'lotes',
                'ubicaciones',
            ])
            ->when(
                $buscar !== '',
                fn ($query) => $query->where(
                    'nombre',
                    'like',
                    "%{$buscar}%"
                )
            )
            ->orderByDesc('activo')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view(
            'v2.parametros.areas.index',
            compact('areas', 'buscar')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:areas,nombre',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        Area::create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'activo' => true,
        ]);

        return redirect()
            ->route('v2.parametros.areas.index')
            ->with('success', 'El área fue creada correctamente.');
    }

    public function update(
        Request $request,
        Area $area
    ): RedirectResponse {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('areas', 'nombre')->ignore($area->id),
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $area->update([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
        ]);

        return redirect()
            ->route('v2.parametros.areas.index')
            ->with('success', 'El área fue actualizada correctamente.');
    }

    public function cambiarEstado(
        Area $area
    ): RedirectResponse {
        $area->update([
            'activo' => !$area->activo,
        ]);

        $mensaje = $area->activo
            ? 'El área fue activada correctamente.'
            : 'El área fue desactivada correctamente.';

        return redirect()
            ->route('v2.parametros.areas.index')
            ->with('success', $mensaje);
    }
}