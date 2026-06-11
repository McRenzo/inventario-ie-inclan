<?php
namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalActivos'    => Bien::count(),
            'activosDisponibles' => Bien::where('estado_actual', 'disponible')->count(),
            'enMantenimiento' => Bien::where('estado_actual', 'en_mantenimiento')->count(),
            // Agrupamos categorías para la barra de progreso
            'categoriasStats' => Categoria::withCount('bienes')->get(),
        ];

        return view('dashboard', compact('data'));
    }
}