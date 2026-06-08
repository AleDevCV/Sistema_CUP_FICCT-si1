<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    =========================================
    LISTAR USUARIOS
    =========================================
    */
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'ilike', "%{$term}%")
                       ->orWhere('email', 'ilike', "%{$term}%");
                });
            })
            ->when($request->filled('role'), function ($q) use ($request) {
                $q->where('role_id', $request->role);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->only(['search', 'role']));

        $roles = Role::all();

        return view(
            'users.index',
            compact('users', 'roles')
        );
    }

    /*
    =========================================
    FORMULARIO CREAR
    =========================================
    */
    public function create()
    {
        $roles = Role::all();

        return view(
            'users.create',
            compact('roles')
        );
    }

    /*
    =========================================
    GUARDAR USUARIO
    =========================================
    */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'role_id' =>
            'required|exists:roles,id',

            'name' =>
            'required|max:100',

            'username' =>
            'required|max:50|unique:users',

            'email' =>
            'required|email|unique:users',

            'password' =>
            'required|min:6',

            'status' =>
            'nullable|boolean'

        ]);

        /*
        Checkbox boolean
        */
        $validated['status'] =
        $request->boolean('status');

        /*
        Crear usuario
        */
        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Usuario creado correctamente'
            );
    }

    /*
    =========================================
    MOSTRAR DETALLE
    =========================================
    */
    public function show(User $user)
    {
        $user->load('role');

        return view(
            'users.show',
            compact('user')
        );
    }

    /*
    =========================================
    FORMULARIO EDITAR
    =========================================
    */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view(
            'users.edit',
            compact(
                'user',
                'roles'
            )
        );
    }

    /*
    =========================================
    ACTUALIZAR USUARIO
    =========================================
    */
    public function update(
        Request $request,
        User $user
    )
    {
        $validated = $request->validate([

            'role_id' =>
            'required|exists:roles,id',

            'name' =>
            'required|max:100',

            'username' =>
            'required|max:50|unique:users,username,' . $user->id,

            'email' =>
            'required|email|unique:users,email,' . $user->id,

            'password' =>
            'nullable|min:6',

            'status' =>
            'nullable|boolean'

        ]);

        /*
        Boolean checkbox
        */
        $validated['status'] =
        $request->boolean('status');

        /*
        Si password viene vacío
        no actualizar
        */
        if (empty($validated['password'])) {

            unset(
                $validated['password']
            );
        }

        /*
        Proteger auto-desactivación y auto-cambio de rol
        */
        if (auth()->id() == $user->id) {
            unset($validated['status'], $validated['role_id']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Usuario actualizado correctamente'
            );
    }

    /*
    =========================================
    ELIMINAR
    =========================================
    */
    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        if ($user->role_id == 1 && User::where('role_id', 1)->count() <= 1) {
            return back()->with('error', 'No puedes eliminar al último administrador del sistema.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Usuario eliminado correctamente'
            );
    }

    /*
    =========================================
    CREAR ADMIN INICIAL
    =========================================
    */
    public function createInitialUser(
        Request $request
    )
    {
        $validated = $request->validate([

            'role_id' =>
            'required|exists:roles,id',

            'name' =>
            'required|max:100',

            'username' =>
            'required|max:50|unique:users',

            'email' =>
            'required|email|unique:users',

            'password' =>
            'required|min:6',

            'status' =>
            'nullable|boolean'

        ]);

        $validated['status'] =
        $request->boolean('status');

        $user = User::create(
            $validated
        );

        return response()->json([

            'message' =>
            'Usuario creado correctamente',

            'data' =>
            $user

        ],201);
    }
}