<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Carrera;
use App\Models\Grupo;
use Illuminate\Http\Request;

class PostulanteController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $postulantes = Postulante::with([

            'primeraCarrera',
            'segundaCarrera',
            'grupo'

        ])
        ->latest()
        ->paginate(10);

        return view(
            'postulantes.index',
            compact('postulantes')
        );
    }


    /*
    Mostrar formulario
    */

    public function create()
    {
        $carreras = Carrera::where(
            'estado',
            true
        )->get();

        $grupos = Grupo::all();

        return view(
            'postulantes.create',
            compact(
                'carreras',
                'grupos'
            )
        );
    }


    /*
    Guardar
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'ci' => 'required|unique:postulantes,ci',
            'nombres' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'nullable|email',
            'colegio' => 'required',
            'ciudad' => 'required',
            'titulo_bachiller' => 'required|max:255',
            'otros_requisitos' => 'nullable',

            'carrera_primera_opcion_id' =>
            'required|exists:carreras,id',

            'carrera_segunda_opcion_id' =>
            'nullable|exists:carreras,id',

            'grupo_id' =>
            'nullable|exists:grupos,id',

            'promedio_final' =>
            'nullable|numeric',

            'estado_final' =>
            'nullable|max:100',

            'estado' =>
            'required|boolean'

        ]);

        Postulante::create($data);

        return redirect()
            ->route(
                'postulantes.index'
            )
            ->with(
                'success',
                'Postulante creado correctamente'
            );
    }


    /*
    Ver detalle
    */

    public function show(
        Postulante $postulante
    )
    {
        $postulante->load([

            'primeraCarrera',
            'segundaCarrera',
            'grupo',
            'pago',
            'examenes'

        ]);

        return view(
            'postulantes.show',
            compact(
                'postulante'
            )
        );
    }


    /*
    Editar
    */

    public function edit(
        Postulante $postulante
    )
    {
        $carreras = Carrera::all();

        $grupos = Grupo::all();

        return view(
            'postulantes.edit',
            compact(
                'postulante',
                'carreras',
                'grupos'
            )
        );
    }


    /*
    Actualizar
    */

    public function update(
        Request $request,
        Postulante $postulante
    )
    {
        $data = $request->validate([

            'ci' =>
            'required|unique:postulantes,ci,' .
            $postulante->id,

            'nombres' =>
            'required|max:255',

            'apellidos' =>
            'required|max:255',

            'fecha_nacimiento' =>
            'required|date',

            'sexo' =>
            'required',

            'direccion' =>
            'required',

            'telefono' =>
            'required',

            'email' =>
            'nullable|email',

            'colegio' =>
            'required',

            'ciudad' =>
            'required',

            'titulo_bachiller' =>
            'required|max:255',

            'otros_requisitos' =>
            'nullable',

            'carrera_primera_opcion_id' =>
            'required|exists:carreras,id',

            'carrera_segunda_opcion_id' =>
            'nullable|exists:carreras,id',

            'grupo_id' =>
            'nullable|exists:grupos,id',

            'promedio_final' =>
            'nullable|numeric',

            'estado_final' =>
            'nullable|max:100',

            'estado' =>
            'required|boolean'

        ]);

        $postulante->update(
            $data
        );

        return redirect()
            ->route(
                'postulantes.index'
            )
            ->with(
                'success',
                'Postulante actualizado correctamente'
            );
    }


    /*
    Eliminar
    */

    public function destroy(
        Postulante $postulante
    )
    {
        $postulante->delete();

        return redirect()
            ->route(
                'postulantes.index'
            )
            ->with(
                'success',
                'Postulante eliminado correctamente'
            );
    }
}