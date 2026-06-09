<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GrupoDocente;
use App\Models\Materia;
use Illuminate\Http\Request;

/**
 * Controlador de Asignaciones Docente-Grupo-Materia — CU09.
 * 
 * CRUD protegido por middleware role:Administrador|Coordinador.
 * Regla de negocio: maximo 4 grupos por docente (carga horaria).
 */
class GrupoDocenteController extends Controller
{
    /**
     * Lista todas las asignaciones con relaciones cargadas.
     */
    public function index()
    {
        $asignaciones = GrupoDocente::with(['docente.user', 'grupo', 'materia'])->latest()->paginate(10);

        return view('asignaciones.index', compact('asignaciones'));
    }

    /**
     * Muestra formulario de creacion con docentes elegibles, grupos y materias activas.
     */
    public function create()
    {
        $docentes = Docente::where('contratado', true)
            ->where('maestria', true)
            ->where('diplomado_educacion_superior', true)
            ->with('user')
            ->get();

        $grupos = Grupo::where('estado', true)->get();

        $materias = Materia::where('estado', true)->get();

        return view('asignaciones.create', compact('docentes', 'grupos', 'materias'));
    }

    /**
     * Almacena una asignacion validando carga horaria maxima (4 grupos).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'docente_id' => ['required', 'exists:docentes,id'],
            'grupo_id'   => ['required', 'exists:grupos,id'],
            'materia_id' => ['required', 'exists:materias,id'],
        ]);

        $docente = Docente::findOrFail($request->docente_id);

        // Validar carga horaria maxima: 4 grupos
        if ($docente->cargaActual() >= 4) {
            return back()->withErrors([
                'docente_id' => 'El docente ha alcanzado la carga horaria máxima permitida (4 grupos).'
            ])->withInput();
        }

        try {
            GrupoDocente::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23505) {
                return back()->withErrors([
                    'docente_id' => 'Esta asignación ya existe (mismo docente, grupo y materia).'
                ])->withInput();
            }
            throw $e;
        }

        return redirect()->route('asignaciones.index')
            ->with('success', 'Asignación creada correctamente.');
    }

    /**
     * Elimina una asignacion.
     */
    public function destroy(GrupoDocente $asignacione)
    {
        $asignacione->delete();

        return redirect()->route('asignaciones.index')
            ->with('success', 'Asignación eliminada correctamente.');
    }
}
