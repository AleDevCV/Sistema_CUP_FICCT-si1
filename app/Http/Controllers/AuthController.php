<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    /*
    Mostrar formulario "Olvidé mi contraseña"
    */

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /*
    Enviar enlace de restablecimiento
    */

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Te hemos enviado un enlace para restablecer tu contraseña.');
        }

        return back()->withErrors(['email' => 'No pudimos encontrar un usuario con ese correo.']);
    }

    /*
    Mostrar formulario de restablecimiento (con token)
    */

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email ?? old('email', '')
        ]);
    }

    /*
    Procesar restablecimiento de contraseña
    */

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Contraseña restablecida correctamente.');
        }

        return back()->withErrors(['email' => 'El token es inválido o ha expirado.']);
    }
}