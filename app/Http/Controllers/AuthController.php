<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /*
    Mostrar login
    */

    public function showLogin()
    {
        return view('auth.login');
    }
    public function showRegister()
{
    return view('auth.register');
}

    /*
    Registrar usuario
    */

   public function register(Request $request)
{
    $data = $request->validate([

        'name' => [
            'required',
            'string',
            'max:255'
        ],

        'username' => [
            'required',
            'string',
            'max:50',
            'unique:users,username'
        ],

        'email' => [
            'required',
            'email',
            'unique:users,email'
        ],

        'password' => [
            'required',
            'min:6',
            'confirmed'
        ]

    ]);

    $user = User::create([

    'role_id' => 1,
    'name' => $data['name'],
    'username' => $data['username'],
    'email' => $data['email'],
    'password' => Hash::make($data['password']),
    'status' => true

]);

    Auth::login($user);

    $request->session()->regenerate();

    return redirect()->route('dashboard');
}

    /*
    Iniciar sesión
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => [
                'required',
                'email'
            ],

            'password' => [
                'required'
            ]

        ]);

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Credenciales incorrectas'
            ])
            ->onlyInput('email');
    }

    /*
    Cerrar sesión
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}