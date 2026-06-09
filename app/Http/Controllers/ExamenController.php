<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\GrupoDocente;
use App\Models\Materia;
use App\Models\Postulante;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador|Coordinador')->only(['create', 'store', 'destroy']);
        $this->middleware('role:Administrador|Coordinador|Docente')->only(['edit', 'update']);
    }

    /*
    Mostrar listado
    */

    public function index()
    {
        $query = Postulante::whereHas('examenes');

        // Docente solo ve postulantes de sus grupos asignados
        if (auth()->user()->hasRole('Docente')) {
            $docente = auth()->user()->docente;

            if ($docente) {
                $gruposAsignados = $docente->grupoDocentes()->pluck('grupo_id');
                $query->whereIn('grupo_id', $gruposAsignados);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $postulantes = $query->with('examenes')->paginate(20);

        // Calcular métricas al vuelo
        $postulantes->getCollection()->transform(function ($postulante) {
            $postulante->total_examenes = $postulante->examenes->count();
            return $postulante;
        });

        return view('examenes.index', compact('postulantes'));
    }


    /*
    Mostrar formulario crear
    */

    public function create()
    {
        if (auth()->user()->hasRole('Docente')) {
            $docente = auth()->user()->docente;

            if ($docente) {
                $grupoIds = GrupoDocente::where('docente_id', $docente->id)->pluck('grupo_id');
                $materiaIds = GrupoDocente::where('docente_id', $docente->id)->pluck('materia_id');

                $postulantes = Postulante::whereIn('grupo_id', $grupoIds)->get();
                $materias = Materia::whereIn('id', $materiaIds)->get();
            } else {
                $postulantes = collect();
                $materias = collect();
            }
        } else {
            $postulantes = Postulante::where('estado', true)->get();
            $materias = Materia::where('estado', true)->get();
        }

        return view('examenes.create', compact('postulantes', 'materias'));
    }


    /*
    Guardar
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'postulante_id' => [

                'required',
                'exists:postulantes,id'

            ],

            'materia_id' => [

                'required',
                'exists:materias,id'

            ],

            'numero_examen' => [

                'required',
                'integer',
                'min:1'

            ],

            'nota' => [

                'required',
                'numeric',
                'min:0',
                'max:100'

            ],

            'porcentaje' => [

                'required',
                'numeric',
                'min:0',
                'max:100'

            ]

        ]);

        Examen::create(
            $data
        );

        return redirect()
            ->route(
                'examenes.index'
            )
            ->with(
                'success',
                'Examen creado correctamente'
            );
    }


    /*
    Mostrar detalle
    */

    public function show(
        Examen $examen
    )
    {
        $examen->load([

            'postulante',
            'materia'

        ]);

        return view(
            'examenes.show',
            compact('examen')
        );
    }


    /*
    Drill-Down: Ver materias y exámenes de un postulante
    */

    public function postulante(Postulante $postulante)
    {
        // Si es Docente, validar que el postulante pertenezca a uno de sus grupos
        if (auth()->user()->hasRole('Docente')) {
            $docente = auth()->user()->docente;

            if ($docente) {
                $gruposAsignados = $docente->grupoDocentes()->pluck('grupo_id');

                if (!$gruposAsignados->contains($postulante->grupo_id)) {
                    abort(403, 'No tienes acceso a este postulante.');
                }
            } else {
                abort(403);
            }
        }

        // Cargar materias activas y agrupar exámenes por materia
        $materias = Materia::where('estado', true)->get();

        $examenesPorMateria = [];

        foreach ($materias as $materia) {
            $examenes = $postulante->examenes()
                ->where('materia_id', $materia->id)
                ->orderBy('numero_examen')
                ->get();

            if ($examenes->isNotEmpty()) {
                $examenesPorMateria[] = [
                    'materia' => $materia,
                    'examenes' => $examenes,
                ];
            }
        }

        return view('examenes.por_postulante', compact('postulante', 'examenesPorMateria'));
    }


    /*
    Mostrar formulario editar
    */

    public function edit(
        Examen $examen
    )
    {
        $postulantes = Postulante::all();

        $materias = Materia::all();

        return view(
            'examenes.edit',
            compact(
                'examen',
                'postulantes',
                'materias'
            )
        );
    }


    /*
    Actualizar
    */

    public function update(
        Request $request,
        Examen $examen
    )
    {
        $data = $request->validate([
            'nota' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $examen->update(['nota' => $data['nota']]);

        return redirect()
            ->route(
                'examenes.index'
            )
            ->with(
                'success',
                'Nota actualizada correctamente'
            );
    }


    /*
    Eliminar
    */

    public function destroy(
        Examen $examen
    )
    {
        $examen->delete();

        return redirect()
            ->route(
                'examenes.index'
            )
            ->with(
                'success',
                'Examen eliminado correctamente'
            );
    }

    /**
     * Generar notas aleatorias para postulantes habilitados (testing).
     */
    public function generarAleatorio()
    {
        $postulantes = Postulante::where('estado_final', 'HABILITADO')->get();
        $materias = Materia::where('estado', true)->get();

        $creados = 0;

        foreach ($postulantes as $postulante) {
            foreach ($materias as $materia) {
                for ($i = 1; $i <= 3; $i++) {
                    Examen::updateOrCreate(
                        [
                            'postulante_id' => $postulante->id,
                            'materia_id'    => $materia->id,
                            'numero_examen' => $i,
                        ],
                        [
                            'nota'       => rand(0, 100),
                            'porcentaje' => 33.33,
                        ]
                    );
                    $creados++;
                }
            }
        }

        return redirect()->route('examenes.index')
            ->with('success', "{$creados} registros de exámenes generados aleatoriamente.");
    }

    /**
     * Vaciar todos los examenes (testing). Solo Admin/Coordinador.
     */
    public function vaciar()
    {
        Examen::truncate();

        return redirect()->route('examenes.index')
            ->with('success', 'Todos los exámenes han sido eliminados.');
    }
}