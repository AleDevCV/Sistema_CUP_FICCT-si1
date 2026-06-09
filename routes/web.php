<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GrupoController;
/*
|--------------------------------------------------------------------------
| Ruta principal
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');


/*
|--------------------------------------------------------------------------
| Crear admin inicial
|--------------------------------------------------------------------------
*/

Route::post(
    '/crear-admin',
    [UserController::class,'createInitialUser']
);


/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function(){

    /*
    Login
    */

    Route::get(
        '/login',
        [AuthController::class,'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class,'login']
    );

    /*
    Registro
    */

    Route::get(
        '/register',
        [AuthController::class,'showRegister']
    )->name('register');

    Route::post(
        '/register',
        [AuthController::class,'register']
    );

    /*
    Olvidé mi contraseña
    */

    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');

});


/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

    /*
    Logout
    */

    Route::post(
        '/logout',
        [AuthController::class,'logout']
    )->name('logout');


    /*
    Dashboard
    */

    Route::get(
        '/dashboard',
        function(){

            return view('dashboard');

        }

    )->name('dashboard');


    /*
    CRUD Usuarios
    */

    Route::resource(
        'users',
        UserController::class
    )->middleware('admin');

    /*
    CRUD Carreras
    */

    Route::resource(
        'carreras',
        CarreraController::class
    )->middleware('role:Administrador|Coordinador');
    Route::resource(
    'roles',
    RoleController::class
)->middleware('admin');
Route::resource(
    'postulantes',
    PostulanteController::class
);
Route::resource(
    'materias',
    MateriaController::class
)->middleware('role:Administrador|Coordinador');
Route::resource(
    'examenes',
    ExamenController::class
);
Route::resource(
    'docentes',
    DocenteController::class
);
Route::resource(
    'grupos',
    GrupoController::class
);
});