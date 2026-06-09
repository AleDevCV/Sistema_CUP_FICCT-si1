<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

/**
 * Controlador de Materias — CU04.
 * 
 * CRUD protegido por middleware role:Administrador|Coordinador.
 * Incluye conteo de ex\u00e1menes y grupo-docentes asociados.
 */
class MateriaController extends Controller
{
    /**
     * Lista materias paginadas con conteo de ex\u00e1menes y grupos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $materias = Materia::withCount([
            'examenes',
            'grupoDocentes'
        ])
        ->latest()
        ->paginate(10);

        return view(
            'materias.index',
            compact('materias')
        );
    }


    /**
     * Muestra formulario de creaci\u00f3n de materia.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view(
            'materias.create'
        );
    }


    /**
     * Almacena una nueva materia con nombre \u00fanico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(
        Request $request
    )
    {
        $data = $request->validate([

            'nombre' => [

                'required',
                'string',
                'max:255',
                'unique:materias,nombre'

            ],

            'descripcion' => [

                'nullable',
                'string'

            ],

            'estado' => [

                'required',
                'boolean'

            ],

            'ponderacion' => [

                'required',
                'numeric',
                'min:0',
                'max:100'

            ]

        ]);

        Materia::create(
            $data
        );

        return redirect()
            ->route(
                'materias.index'
            )
            ->with(
                'success',
                'Materia creada correctamente'
            );
    }


    /**
     * Muestra detalle de materia con ex\u00e1menes y grupos cargados.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function show(
        Materia $materia
    )
    {
        $materia->load([

            'examenes',
            'grupoDocentes'

        ]);

        return view(
            'materias.show',
            compact('materia')
        );
    }


    /**
     * Muestra formulario de edici\u00f3n de materia.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function edit(
        Materia $materia
    )
    {
        return view(
            'materias.edit',
            compact('materia')
        );
    }


    /**
     * Actualiza materia. unique en nombre ignora el ID actual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(
        Request $request,
        Materia $materia
    )
    {
        $data = $request->validate([

            'nombre' => [

                'required',
                'string',
                'max:255',
                'unique:materias,nombre,' .
                $materia->id

            ],

            'descripcion' => [

                'nullable',
                'string'

            ],

            'estado' => [

                'required',
                'boolean'

            ],

            'ponderacion' => [

                'required',
                'numeric',
                'min:0',
                'max:100'

            ]

        ]);

        $materia->update(
            $data
        );

        return redirect()
            ->route(
                'materias.index'
            )
            ->with(
                'success',
                'Materia actualizada correctamente'
            );
    }


    /**
     * Elimina materia solo si no tiene ex\u00e1menes ni grupos asociados.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(
        Materia $materia
    )
    {
        if(
            $materia->examenes()->count()>0
            ||
            $materia->grupoDocentes()->count()>0
        )
        {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'No puedes eliminar una materia relacionada'
                );
        }

        $materia->delete();

        return redirect()
            ->route(
                'materias.index'
            )
            ->with(
                'success',
                'Materia eliminada correctamente'
            );
    }
}