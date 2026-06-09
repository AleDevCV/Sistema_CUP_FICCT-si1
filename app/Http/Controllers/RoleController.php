<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

/**
 * Controlador de Roles — CU03.
 * 
 * CRUD protegido por middleware admin.
 * Usa Spatie\Permission\Models\Role con withCount('users'),
 * protege eliminación de roles con usuarios asignados.
 */
class RoleController extends Controller
{
    /**
     * Lista roles paginados con conteo de usuarios asignados.
     *
     * @return \Illuminate\View\View
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

    /**
     * Muestra formulario de creaci\u00f3n de rol.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view(
            'roles.create'
        );
    }

    /**
     * Crea un rol Spatie con name \u00fanico y guard_name='web'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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

    /**
     * Muestra detalle del rol con usuarios asignados cargados.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function show(Role $role)
    {
        $role->load('users');

        return view(
            'roles.show',
            compact('role')
        );
    }

    /**
     * Muestra formulario de edici\u00f3n de rol.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view(
            'roles.edit',
            compact('role')
        );
    }

    /**
     * Actualiza rol. unique en name ignora el ID actual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
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

    /**
     * Elimina rol solo si no tiene usuarios asignados.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
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