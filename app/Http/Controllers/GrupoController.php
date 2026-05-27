<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $grupos = Grupo::withCount([

            'postulantes',
            'grupoDocentes'

        ])
        ->latest()
        ->paginate(10);

        return view(
            'grupos.index',
            compact('grupos')
        );
    }


    /*
    Mostrar formulario crear
    */

    public function create()
    {
        return view(
            'grupos.create'
        );
    }


    /*
    Guardar
    */

    public function store(
        Request $request
    )
    {
        $data = $request->validate([

            'nombre' => [

                'required',
                'max:255'

            ],

            'codigo' => [

                'required',
                'unique:grupos,codigo',
                'max:50'

            ],

            'aula' => [

                'required',
                'max:100'

            ],

            'horario' => [

                'required',
                'max:255'

            ],

            'capacidad_maxima' => [

                'required',
                'integer',
                'min:1'

            ],

            'estado' => [

                'required',
                'boolean'

            ]

        ]);

        Grupo::create(
            $data
        );

        return redirect()
            ->route(
                'grupos.index'
            )
            ->with(
                'success',
                'Grupo creado correctamente'
            );
    }


    /*
    Mostrar detalle
    */

    public function show(
        Grupo $grupo
    )
    {
        $grupo->load([

            'postulantes',
            'grupoDocentes.docente',
            'grupoDocentes.materia'

        ]);

        return view(
            'grupos.show',
            compact('grupo')
        );
    }


    /*
    Mostrar formulario editar
    */

    public function edit(
        Grupo $grupo
    )
    {
        return view(
            'grupos.edit',
            compact('grupo')
        );
    }


    /*
    Actualizar
    */

    public function update(
        Request $request,
        Grupo $grupo
    )
    {
        $data = $request->validate([

            'nombre' => [

                'required',
                'max:255'

            ],

            'codigo' => [

                'required',
                'max:50',
                'unique:grupos,codigo,' .
                $grupo->id

            ],

            'aula' => [

                'required',
                'max:100'

            ],

            'horario' => [

                'required',
                'max:255'

            ],

            'capacidad_maxima' => [

                'required',
                'integer',
                'min:1'

            ],

            'estado' => [

                'required',
                'boolean'

            ]

        ]);

        $grupo->update(
            $data
        );

        return redirect()
            ->route(
                'grupos.index'
            )
            ->with(
                'success',
                'Grupo actualizado correctamente'
            );
    }


    /*
    Eliminar
    */

    public function destroy(
        Grupo $grupo
    )
    {
        if(
            $grupo->postulantes()->count()>0
            ||
            $grupo->grupoDocentes()->count()>0
        )
        {
            return back()
            ->with(
                'error',
                'No puedes eliminar un grupo relacionado'
            );
        }

        $grupo->delete();

        return redirect()
            ->route(
                'grupos.index'
            )
            ->with(
                'success',
                'Grupo eliminado correctamente'
            );
    }
}