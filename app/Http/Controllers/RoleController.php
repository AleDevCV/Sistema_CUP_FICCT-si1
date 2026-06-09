<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $roles = Role::withCount('users')
            ->latest()
            ->paginate(10);

        return view(
            'roles.index',
            compact('roles')
        );
    }

    /*
    Mostrar formulario crear
    */

    public function create()
    {
        return view(
            'roles.create'
        );
    }

    /*
    Guardar
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => [

                'required',
                'string',
                'max:100',
                'unique:roles,name'

            ],

            'description' => [

                'nullable',
                'string',
                'max:255'

            ]

        ]);

        Role::create($data);

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol creado correctamente'
            );
    }

    /*
    Mostrar detalle
    */

    public function show(Role $role)
    {
        $role->load('users');

        return view(
            'roles.show',
            compact('role')
        );
    }

    /*
    Mostrar formulario editar
    */

    public function edit(Role $role)
    {
        return view(
            'roles.edit',
            compact('role')
        );
    }

    /*
    Actualizar
    */

    public function update(
        Request $request,
        Role $role
    )
    {
        $data = $request->validate([

            'name' => [

                'required',
                'string',
                'max:100',
                'unique:roles,name,' . $role->id

            ],

            'description' => [

                'nullable',
                'string',
                'max:255'

            ]

        ]);

        $role->update($data);

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol actualizado correctamente'
            );
    }

    /*
    Eliminar
    */

    public function destroy(
        Role $role
    )
    {
        if($role->users()->count()>0)
        {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'No puedes eliminar un rol que tiene usuarios asignados'
                );
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol eliminado correctamente'
            );
    }
}