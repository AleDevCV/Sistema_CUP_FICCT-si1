<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Muestra formulario de edición del perfil autenticado.
     */
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Actualiza perfil y contraseña opcional.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        // Cambio de contraseña opcional
        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => ['required', function ($attr, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('La contraseña actual no coincide.');
                    }
                }],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
