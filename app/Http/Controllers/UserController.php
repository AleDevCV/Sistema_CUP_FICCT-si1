<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;

/**
 * Controlador de Gestión de Usuarios — CU02.
 * 
 * CRUD completo protegido por el middleware EnsureUserIsAdmin.
 * Incluye búsqueda con ILIKE, filtro por rol, protecciones contra
 * auto-desactivación, auto-cambio de rol y eliminación del último admin.
 */
class UserController extends Controller
{
    /**
     * Lista usuarios paginados con eager-load del rol.
     * Soporta búsqueda por nombre/email (ILIKE) y filtro por role_id.
     * Los parámetros de query se conservan en la paginación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Construir query base con eager-load de roles (Spatie)
        $users = User::with('roles')
            // Búsqueda insensible a mayúsculas en name y email
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'ilike', "%{$term}%")
                       ->orWhere('email', 'ilike', "%{$term}%");
                });
            })
            // Filtro por rol usando Spatie
            ->when($request->filled('role'), function ($q) use ($request) {
                $q->whereHas('roles', fn ($sub) => $sub->where('id', $request->role));
            })
            ->latest()
            ->paginate(10)
            ->appends($request->only(['search', 'role']));

        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Muestra formulario de creación de usuario con selector de roles.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario en la BD.
     * Convierte el checkbox 'status' a booleano real.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:100',
            'username' => 'required|max:50|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'status'   => 'nullable|boolean',
            'role'     => 'required|exists:roles,name',
        ]);

        $validated['status'] = $request->boolean('status');
        $roleName = $validated['role'];
        unset($validated['role']);

        $user = User::create($validated);
        $user->assignRole($roleName);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Muestra el detalle de un usuario con su rol cargado.
     * Usa route-model binding implícito.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    /**
     * Muestra formulario de edición con datos actuales y selector de roles.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza un usuario existente.
     * 
     * Reglas de negocio:
     * - Si el admin se edita a sí mismo, se ignoran status y role_id.
     * - Contraseña vacía = no se modifica.
     * - username/email únicos excluyendo el ID del usuario editado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|max:100',
            'username' => 'required|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'status'   => 'nullable|boolean',
            'role'     => 'nullable|exists:roles,name',
        ]);

        // Checkbox a booleano
        $validated['status'] = $request->boolean('status');

        // No modificar contraseña si viene vacía
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Gestionar rol
        $roleName = null;
        if ($request->filled('role') && auth()->id() != $user->id) {
            $roleName = $validated['role'];
        }
        unset($validated['role']);

        // Protección: el admin no puede desactivarse a sí mismo
        if (auth()->id() == $user->id) {
            unset($validated['status']);
        }

        $user->update($validated);

        if ($roleName) {
            $user->syncRoles($roleName);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Elimina un usuario. Protege auto-eliminación y último admin.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // El admin no puede eliminarse a sí mismo
        if (auth()->id() == $user->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Bloquear eliminación del último administrador del sistema
        if ($user->hasRole('Administrador') && User::role('Administrador')->count() <= 1) {
            return back()->with('error', 'No puedes eliminar al último administrador del sistema.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
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