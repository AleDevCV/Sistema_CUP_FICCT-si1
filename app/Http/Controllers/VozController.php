<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Postulante;
use Illuminate\Http\Request;

class VozController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Autoridad');
    }

    public function index()
    {
        return view('reportes.voz');
    }

    public function procesarComando(Request $request)
    {
        $comando = mb_strtolower(trim($request->input('comando', '')));

        if (str_contains($comando, 'aprobado')) {
            $count = Postulante::where('estado_final', 'APROBADO')->count();
            $mensaje = "Actualmente hay {$count} postulantes aprobados.";
        } elseif (str_contains($comando, 'reprobado')) {
            $count = Postulante::where('estado_final', 'REPROBADO')->count();
            $mensaje = "Se registran {$count} postulantes reprobados.";
        } elseif (str_contains($comando, 'inscrito') || str_contains($comando, 'total')) {
            $count = Postulante::count();
            $mensaje = "El total de postulantes inscritos es {$count}.";
        } elseif (str_contains($comando, 'grupo')) {
            $count = Grupo::where('estado', true)->count();
            $mensaje = "Hay {$count} grupos habilitados.";
        } elseif (str_contains($comando, 'habilitado')) {
            $count = Postulante::where('estado_final', 'HABILITADO')->count();
            $mensaje = "Hay {$count} postulantes habilitados.";
        } elseif (str_contains($comando, 'dashboard') || str_contains($comando, 'panel') || str_contains($comando, 'inicio')) {
            $mensaje = "Redirigiendo al dashboard.";
        } else {
            $mensaje = "Comando no reconocido. Prueba preguntando por aprobados, reprobados, inscritos, grupos o habilitados.";
        }

        return response()->json(['mensaje' => $mensaje]);
    }
}
