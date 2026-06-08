<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

/**
 * Controlador de Autenticación — CU01.
 * 
 * Gestiona login, registro, logout y recuperación de contraseña
 * usando el guard web nativo de Laravel y el facade Password.
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Muestra el formulario de registro público.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Registra un nuevo usuario con rol Administrador por defecto,
     * inicia sesión automáticamente y regenera la sesión.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validar datos de entrada
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        // Crear usuario con rol Admin por defecto y contraseña hasheada
        $user = User::create([
            'role_id'  => 1,
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => true,
        ]);

        // Autenticar y regenerar ID de sesión (previene session fixation)
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Valida credenciales (email + password) contra la BD.
     * Regenera la sesión en caso de éxito para mitigar session fixation.
     * Retorna error genérico en fallo sin revelar qué campo fue incorrecto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['email' => 'Credenciales incorrectas'])
            ->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario autenticado:
     * logout, invalida sesión, regenera token CSRF y redirige al login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Muestra el formulario "Olvidé mi contraseña".
     *
     * @return \Illuminate\View\View
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace de restablecimiento de contraseña al email proporcionado.
     * Usa el PasswordBroker de Laravel que persiste el token en password_reset_tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Te hemos enviado un enlace para restablecer tu contraseña.')
            : back()->withErrors(['email' => 'No pudimos encontrar un usuario con ese correo.']);
    }

    /**
     * Muestra el formulario de restablecimiento con token.
     * El email puede venir del query string o de un intento anterior (old).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email ?? old('email', ''),
        ]);
    }

    /**
     * Procesa el restablecimiento de contraseña.
     * Valida token + email + nueva contraseña (mín 6, confirmada).
     * Usa Password::reset() que verifica el token contra password_reset_tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // forceFill + save para persistir sin disparar eventos ni mutators innecesarios
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Contraseña restablecida correctamente.')
            : back()->withErrors(['email' => 'El token es inválido o ha expirado.']);
    }
}