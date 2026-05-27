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
    );

    /*
    CRUD Carreras
    */

    Route::resource(
        'carreras',
        CarreraController::class
    );
    Route::resource(
    'roles',
    RoleController::class
);
Route::resource(
    'postulantes',
    PostulanteController::class
);
Route::resource(
    'materias',
    MateriaController::class
);
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