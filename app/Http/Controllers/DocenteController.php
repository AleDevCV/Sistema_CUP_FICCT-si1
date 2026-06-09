<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controlador de Docentes — CU05.
 * 
 * CRUD protegido por middleware role:Administrador|Coordinador.
 * Regla de negocio: maestría Y diplomado deben ser true para crear/actualizar.
 */
class DocenteController extends Controller
{
    /**
     * Lista docentes paginados con usuario asociado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $docentes = Docente::with('user')->latest()->paginate(10);
        return view('docentes.index', compact('docentes'));
    }

    /**
     * Muestra formulario de creación. Solo usuarios sin docente asignado.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::role('Docente')->whereDoesntHave('docente')->get();
        return view('docentes.create', compact('users'));
    }

    /**
     * Almacena docente. Bloquea si no cumple maestría Y diplomado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:docentes,user_id'],
            'ci'      => ['required', 'unique:docentes,ci', 'max:30'],
            'telefono' => ['required', 'max:30'],
            'maestria'  => ['required', 'boolean'],
            'diplomado_educacion_superior' => ['required', 'boolean'],
            'contratado' => ['required', 'boolean'],
            'estado'   => ['required', 'boolean'],
        ]);

        // Candado lógico CU05: ambas banderas deben ser true
        if (!$request->boolean('maestria') || !$request->boolean('diplomado_educacion_superior')) {
            return back()->withErrors([
                'credenciales' => 'Regla de Negocio: El docente debe poseer obligatoriamente Maestría y Diplomado para ser habilitado.'
            ])->withInput();
        }

        Docente::create($data);

        return redirect()->route('docentes.index')
            ->with('success', 'Docente creado correctamente');
    }


    /**
     * Muestra detalle de docente con usuario y grupos cargados.
     *
     * @param  \App\Models\Docente  $docente
     * @return \Illuminate\View\View
     */
    public function show(Docente $docente)
    {
        $docente->load('user', 'grupoDocentes');
        return view('docentes.show', compact('docente'));
    }

    /**
     * Muestra formulario de edición con todos los usuarios disponibles.
     *
     * @param  \App\Models\Docente  $docente
     * @return \Illuminate\View\View
     */
    public function edit(Docente $docente)
    {
        $users = User::role('Docente')->whereDoesntHave('docente')->orWhere('id', $docente->user_id)->get();
        return view('docentes.edit', compact('docente', 'users'));
    }

    /**
     * Actualiza docente. unique ignora ID actual. Aplica candado CU05.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Docente  $docente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Docente $docente)
    {
        $data = $request->validate([
            'user_id'  => ['required', 'exists:users,id', 'unique:docentes,user_id,' . $docente->id],
            'ci'       => ['required', 'max:30', 'unique:docentes,ci,' . $docente->id],
            'telefono' => ['required', 'max:30'],
            'maestria' => ['required', 'boolean'],
            'diplomado_educacion_superior' => ['required', 'boolean'],
            'contratado'=> ['required', 'boolean'],
            'estado'   => ['required', 'boolean'],
        ]);

        // Candado lógico CU05: ambas banderas deben ser true
        if (!$request->boolean('maestria') || !$request->boolean('diplomado_educacion_superior')) {
            return back()->withErrors([
                'credenciales' => 'Regla de Negocio: El docente debe poseer obligatoriamente Maestría y Diplomado para ser habilitado.'
            ])->withInput();
        }

        $docente->update($data);

        return redirect()->route('docentes.index')
            ->with('success', 'Docente actualizado correctamente');
    }

    /**
     * Elimina docente. cascadeOnDelete limpia automáticamente grupo_docentes.
     *
     * @param  \App\Models\Docente  $docente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Docente $docente)
    {
        $docente->delete();

        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado correctamente');
    }
}
