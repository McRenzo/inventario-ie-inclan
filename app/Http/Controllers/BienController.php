<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\Estado;
use App\Imports\BienesImport; // 1. 👇 IMPORTAMOS LA CLASE QUE CREAMOS
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // 2. 👇 IMPORTAMOS LA FACHADA DE EXCEL

class BienController extends Controller
{
    public function index(Request $request)
    {
        $query = Bien::with(['categoria', 'ubicacion', 'estado']);

        // Aplicar filtro si existe
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('codigo_barras_qr', 'like', '%' . $request->search . '%');
        }

        $bienes = $query->paginate(20)->withQueryString(); // .withQueryString() es la clave aquí
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();
        $estados = Estado::all();

        return view('bienes.index', compact('bienes', 'categorias', 'ubicaciones', 'estados'));
    }

    // 3. Muestra la vista del formulario de importación
    public function importForm()
    {
        return view('bienes.import');
    }

    // 4. Procesa el archivo subido
    public function import(Request $request)
    {
        // Validamos el tamaño máximo y la presencia del campo
        $request->validate([
            'excel_file' => 'required|max:10240',
            'categoria_nombre' => 'required|string|max:255'
        ]);

        $file = $request->file('excel_file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Validación manual de extensión blindada contra fallos de tipo MIME en Windows
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return back()->withErrors(['excel_file' => 'El formato del archivo debe ser obligatoriamente .xlsx, .xls o .csv']);
        }

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\BienesImport($request->input('categoria_nombre')), 
                $file
            );
            
            return redirect()->route('bienes.index')->with('success', '¡Archivo histórico procesado y activos individualizados con éxito!');
        } catch (\Exception $e) {
            return back()->withErrors(['excel_file' => 'Error crítico al procesar las celdas: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        // Obtenemos los datos de tus tablas relacionales
        $categorias = \App\Models\Categoria::all();
        $ubicaciones = \App\Models\Ubicacion::all();
        $estados = \App\Models\Estado::all(); // O como hayas nombrado a tu tabla de estados

        return view('bienes.create', compact('categorias', 'ubicaciones', 'estados'));
    }

    public function store(Request $request)
    {
        // 1. Agregamos 'procedencia' a la validación
        $validated = $request->validate([
            'nombre'               => 'required|string',
            'descripcion'          => 'nullable|string',
            'numero_serie'         => 'nullable|string',
            'procedencia'          => 'nullable|string', // <--- ¡AQUÍ ESTABA EL ERROR!
            'fecha_ingreso_origen' => 'nullable|date',
            'ubicacion_nombre'     => 'required|string',
            'estado_nombre'        => 'required|string',
            'categoria_id'         => 'required|exists:categorias,id',
        ]);

        // 2. Buscamos o creamos la Ubicación
        $ubicacion = \App\Models\Ubicacion::firstOrCreate([
            'nombre' => strtoupper(trim($request->ubicacion_nombre))
        ]);

        // 3. Buscamos o creamos el Estado
        $estado = \App\Models\Estado::firstOrCreate([
            'nombre' => strtoupper(trim($request->estado_nombre))
        ]);

        $codigo = 'INC-' . now()->format('y') . '-' . strtoupper(\Illuminate\Support\Str::random(5));

        // 4. Creamos el bien usando el array validado
        \App\Models\Bien::create([
            'codigo_barras_qr'     => $codigo,
            'nombre'               => $validated['nombre'],
            'descripcion'          => $validated['descripcion'] ?? '',
            'numero_serie'         => $validated['numero_serie'] ?? null,
            'procedencia'          => $validated['procedencia'] ?? null, // Usar el valor validado
            'fecha_ingreso_origen' => $validated['fecha_ingreso_origen'] ?? null,
            'categoria_id'         => $validated['categoria_id'],
            'ubicacion_id'         => $ubicacion->id,
            'estado_id'            => $estado->id,
            'estado_actual'        => 'disponible'
        ]);

        return redirect()->route('bienes.index')->with('success', 'Bien registrado con éxito.');
    }

    public function edit($id, Request $request)
    {
        $bien = Bien::findOrFail($id);
        $categorias = Categoria::all();
        
        // Aplicamos los mismos filtros que en el index para que no se pierdan al editar
        $query = Bien::with(['categoria', 'ubicacion', 'estado']);
        
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('codigo_barras_qr', 'like', '%' . $request->search . '%');
        }

        return view('bienes.index', [
            'bienes' => $query->paginate(20)->withQueryString(),
            'categorias' => $categorias,
            'ubicaciones' => Ubicacion::all(),
            'estados' => Estado::all(),
            'bienEditar' => $bien,
        ]);
    }

    public function update(Request $request, Bien $bien)
    {
        // Lógica similar al store, pero buscando registros existentes
        $ubicacion = \App\Models\Ubicacion::firstOrCreate(['nombre' => strtoupper(trim($request->ubicacion_nombre))]);
        $estado = \App\Models\Estado::firstOrCreate(['nombre' => strtoupper(trim($request->estado_nombre))]);

        $bien->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion ?? '',
            'numero_serie' => $request->numero_serie,
            'procedencia' => $request->procedencia,
            'categoria_id' => $request->categoria_id,
            'ubicacion_id' => $ubicacion->id,
            'estado_id' => $estado->id,
        ]);

        $page = $request->input('page', 1);
        return redirect()->route('bienes.index', ['page' => $page])
                        ->with('success', 'Bien actualizado correctamente.');
    }
    public function destroy(Request $request, Bien $bien)
    {
        // Verificamos que el código ingresado coincida con el código del bien
        if ($request->input('codigo_confirmacion') !== $bien->codigo_barras_qr) {
            return back()->withErrors(['error' => 'El código ingresado no coincide.']);
        }

        $bien->delete();

        return redirect()->route('bienes.index')->with('success', 'Activo eliminado correctamente.');
    }
}