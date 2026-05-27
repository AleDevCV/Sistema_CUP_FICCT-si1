<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Postulante;
use App\Models\Materia;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $examenes = Examen::with([

            'postulante',
            'materia'

        ])
        ->latest()
        ->paginate(10);

        return view(
            'examenes.index',
            compact('examenes')
        );
    }


    /*
    Mostrar formulario crear
    */

    public function create()
    {
        $postulantes = Postulante::where(
            'estado',
            true
        )->get();

        $materias = Materia::where(
            'estado',
            true
        )->get();

        return view(
            'examenes.create',
            compact(
                'postulantes',
                'materias'
            )
        );
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

        $examen->update(
            $data
        );

        return redirect()
            ->route(
                'examenes.index'
            )
            ->with(
                'success',
                'Examen actualizado correctamente'
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
}