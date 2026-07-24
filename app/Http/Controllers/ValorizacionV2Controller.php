<?php

namespace App\Http\Controllers;

use App\Models\UnidadBien;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ValorizacionV2Controller extends Controller
{
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('buscar', ''));

        $unidades = UnidadBien::query()
            ->with([
                'bien',
                'ubicacion',
                'estadoConservacion',
            ])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('codigo_interno', 'like', "%{$busqueda}%")
                        ->orWhere('numero_serie', 'like', "%{$busqueda}%")
                        ->orWhereHas('bien', function ($bienQuery) use ($busqueda) {
                            $bienQuery->where('nombre', 'like', "%{$busqueda}%")
                                ->orWhere('marca', 'like', "%{$busqueda}%")
                                ->orWhere('modelo', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('v2.valorizacion.index', compact(
            'unidades',
            'busqueda'
        ));
    }

    public function edit(UnidadBien $unidad)
    {
        $unidad->load([
            'bien',
            'ubicacion',
            'estadoConservacion',
        ]);

        return view('v2.valorizacion.edit', compact('unidad'));
    }

    public function update(Request $request, UnidadBien $unidad)
    {
        $data = $request->validate([
            'valor_adquisicion' => ['required', 'numeric', 'min:0'],
            'valor_residual' => ['nullable', 'numeric', 'min:0'],
            'valor_ajustado' => ['nullable', 'numeric', 'min:0'],
            'vida_util_meses' => ['required', 'integer', 'min:1', 'max:1200'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $valorAdquisicion = (float) $data['valor_adquisicion'];
        $valorResidual = (float) ($data['valor_residual'] ?? 0);
        $vidaUtilMeses = (int) $data['vida_util_meses'];

        if ($valorResidual > $valorAdquisicion) {
            return back()
                ->withErrors([
                    'valor_residual' => 'El valor residual no puede ser mayor al valor de adquisición.',
                ])
                ->withInput();
        }

        $fechaBase = $unidad->fecha_adquisicion
            ?? $unidad->fecha_ingreso
            ?? $unidad->created_at
            ?? now();

        $fechaInicio = Carbon::parse($fechaBase)->startOfDay();
        $fechaActual = now()->startOfDay();

        $mesesTranscurridos =
            (($fechaActual->year - $fechaInicio->year) * 12)
            + ($fechaActual->month - $fechaInicio->month);

        if ($fechaActual->day < $fechaInicio->day) {
            $mesesTranscurridos--;
        }

        $mesesTranscurridos = max($mesesTranscurridos, 0);

        $mesesDepreciados = min($mesesTranscurridos, $vidaUtilMeses);

        $baseDepreciable = max($valorAdquisicion - $valorResidual, 0);

        $depreciacionMensual = $vidaUtilMeses > 0
            ? $baseDepreciable / $vidaUtilMeses
            : 0;

        $depreciacionAcumulada = round(
            $depreciacionMensual * $mesesDepreciados,
            2
        );

        $valorEnLibros = round(
            max($valorAdquisicion - $depreciacionAcumulada, $valorResidual),
            2
        );

        $unidad->forceFill([
            'valor_adquisicion' => $valorAdquisicion,
            'valor_residual' => $valorResidual,
            'depreciacion_acumulada' => $depreciacionAcumulada,
            'valor_en_libros' => $valorEnLibros,
            'valor_ajustado' => $data['valor_ajustado'] ?? null,
            'vida_util_meses' => $vidaUtilMeses,
            'observaciones' => $data['observaciones'] ?? null,
        ])->save();

        return redirect()
            ->route('v2.valorizacion.index')
            ->with('success', 'Valorización actualizada correctamente.');
    }
}