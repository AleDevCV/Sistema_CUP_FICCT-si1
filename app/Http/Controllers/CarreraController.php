<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Materia;
use Illuminate\Http\Request;

/**
 * Controlador de Carreras — CU04.
 * 
 * CRUD protegido por middleware role:Administrador|Coordinador.
 * Incluye tabla pivote carrera_materia para vincular materias activas.
 */
class CarreraController extends Controller
{
    /**
     * Lista todas las carreras paginadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $carreras = Carrera::latest()->paginate(10);

        return view(
            'carreras.index',
            compact('carreras')
        );
    }


    /**
     * Muestra formulario de creación con materias activas para checkboxes.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $materias = Materia::where('estado', true)->get();
        return view('carreras.create', compact('materias'));
    }


    /**
     * Almacena una carrera y sincroniza sus materias v\u00eda tabla pivote.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:20', 'unique:carreras,codigo'],
            'nombre' => ['required', 'string', 'max:255', 'unique:carreras,nombre'],
            'cupo'   => ['required', 'integer', 'min:0'],
            'gestion'=> ['required', 'integer'],
            'estado' => ['required', 'boolean'],
            'materias' => ['nullable', 'array'],
            'materias.*' => ['exists:materias,id'],
        ]);

        $materias = $request->input('materias', []);
        unset($data['materias']);

        $carrera = Carrera::create($data);
        $carrera->materias()->sync($materias);

        return redirect()->route('carreras.index')
            ->with('success', 'Carrera creada correctamente');
    }


    /**
     * Muestra detalle de una carrera (route-model binding).
     *
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\View\View
     */
    public function show(Carrera $carrera)
    {
        return view(
            'carreras.show',
            compact('carrera')
        );
    }


    /**
     * Muestra formulario de edici\u00f3n con materias activas y las ya asignadas checked.
     *
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\View\View
     */
    public function edit(Carrera $carrera)
    {
        $materias = Materia::where('estado', true)->get();
        return view('carreras.edit', compact('carrera', 'materias'));
    }


    /**
     * Actualiza carrera y re-sincroniza materias en tabla pivote.
     * unique ignora el ID actual para nombre y c\u00f3digo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Carrera $carrera)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:20', 'unique:carreras,codigo,' . $carrera->id],
            'nombre' => ['required', 'string', 'max:255', 'unique:carreras,nombre,' . $carrera->id],
            'cupo'   => ['required', 'integer', 'min:0'],
            'gestion'=> ['required', 'integer'],
            'estado' => ['required', 'boolean'],
            'materias' => ['nullable', 'array'],
            'materias.*' => ['exists:materias,id'],
        ]);

        $materias = $request->input('materias', []);
        unset($data['materias']);

        $carrera->update($data);
        $carrera->materias()->sync($materias);

        return redirect()->route('carreras.index')
            ->with('success', 'Carrera actualizada correctamente');
    }


    /**
     * Elimina una carrera. cascadeOnDelete limpia autom\u00e1ticamente carrera_materia.
     *
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(
        Carrera $carrera
    )
    {
        $carrera->delete();

        return redirect()
            ->route('carreras.index')
            ->with(
                'success',
                'Carrera eliminada correctamente'
            );
    }
}