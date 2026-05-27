<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /*
    Mostrar listado
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


    /*
    Mostrar formulario crear
    */

    public function create()
    {
        return view(
            'materias.create'
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


    /*
    Ver detalle
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


    /*
    Mostrar formulario editar
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


    /*
    Actualizar
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


    /*
    Eliminar
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