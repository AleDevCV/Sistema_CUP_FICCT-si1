<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Materia;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $carreras = Carrera::latest()->paginate(10);

        return view(
            'carreras.index',
            compact('carreras')
        );
    }


    /*
    Mostrar formulario crear
    */

    public function create()
    {
        $materias = Materia::where('estado', true)->get();
        return view('carreras.create', compact('materias'));
    }


    /*
    Guardar
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


    /*
    Mostrar una carrera
    */

    public function show(Carrera $carrera)
    {
        return view(
            'carreras.show',
            compact('carrera')
        );
    }


    /*
    Mostrar formulario editar
    */

    public function edit(Carrera $carrera)
    {
        $materias = Materia::where('estado', true)->get();
        return view('carreras.edit', compact('carrera', 'materias'));
    }


    /*
    Actualizar
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


    /*
    Eliminar
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