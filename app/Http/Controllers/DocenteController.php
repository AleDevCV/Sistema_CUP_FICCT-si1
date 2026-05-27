<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $docentes = Docente::with(
            'user'
        )
        ->latest()
        ->paginate(10);

        return view(
            'docentes.index',
            compact('docentes')
        );
    }


    /*
    Mostrar formulario crear
    */

    public function create()
    {
        $users = User::whereDoesntHave(
            'docente'
        )->get();

        return view(
            'docentes.create',
            compact('users')
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

            'user_id' => [

                'required',
                'exists:users,id',
                'unique:docentes,user_id'

            ],

            'ci' => [

                'required',
                'unique:docentes,ci',
                'max:30'

            ],

            'nombres' => [

                'required',
                'max:255'

            ],

            'apellidos' => [

                'required',
                'max:255'

            ],

            'telefono' => [

                'required',
                'max:30'

            ],

            'email' => [

                'required',
                'email'

            ],

            'profesion' => [

                'required',
                'max:255'

            ],

            'maestria' => [

                'required',
                'boolean'

            ],

            'diplomado_educacion_superior' => [

                'required',
                'boolean'

            ],

            'contratado' => [

                'required',
                'boolean'

            ],

            'estado' => [

                'required',
                'boolean'

            ]

        ]);

        Docente::create(
            $data
        );

        return redirect()
            ->route(
                'docentes.index'
            )
            ->with(
                'success',
                'Docente creado correctamente'
            );
    }


    /*
    Ver detalle
    */

    public function show(
        Docente $docente
    )
    {
        $docente->load(
            'user',
            'grupoDocentes'
        );

        return view(
            'docentes.show',
            compact('docente')
        );
    }


    /*
    Editar
    */

    public function edit(
        Docente $docente
    )
    {
        $users = User::all();

        return view(
            'docentes.edit',
            compact(
                'docente',
                'users'
            )
        );
    }


    /*
    Actualizar
    */

    public function update(
        Request $request,
        Docente $docente
    )
    {
        $data = $request->validate([

            'user_id' => [

                'required',
                'exists:users,id',
                'unique:docentes,user_id,' .
                $docente->id

            ],

            'ci' => [

                'required',
                'max:30',
                'unique:docentes,ci,' .
                $docente->id

            ],

            'nombres' => [

                'required',
                'max:255'

            ],

            'apellidos' => [

                'required',
                'max:255'

            ],

            'telefono' => [

                'required',
                'max:30'

            ],

            'email' => [

                'required',
                'email'

            ],

            'profesion' => [

                'required',
                'max:255'

            ],

            'maestria' => [

                'required',
                'boolean'

            ],

            'diplomado_educacion_superior' => [

                'required',
                'boolean'

            ],

            'contratado' => [

                'required',
                'boolean'

            ],

            'estado' => [

                'required',
                'boolean'

            ]

        ]);

        $docente->update(
            $data
        );

        return redirect()
            ->route(
                'docentes.index'
            )
            ->with(
                'success',
                'Docente actualizado correctamente'
            );
    }


    /*
    Eliminar
    */

    public function destroy(
        Docente $docente
    )
    {
        if(
            $docente->grupoDocentes()->count()>0
        )
        {
            return back()
                ->with(
                    'error',
                    'No puedes eliminar un docente con asignaciones'
                );
        }

        $docente->delete();

        return redirect()
            ->route(
                'docentes.index'
            )
            ->with(
                'success',
                'Docente eliminado correctamente'
            );
    }
}