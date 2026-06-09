<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Postulante;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInscritos   = Postulante::count();
        $totalHabilitados = Postulante::where('estado_final', 'HABILITADO')->count();
        $totalPendientes  = Postulante::where('estado_final', 'PENDIENTE')->count();
        $totalAprobados   = Postulante::where('estado_final', 'APROBADO')->count();
        $totalReprobados  = Postulante::where('estado_final', 'REPROBADO')->count();
        $totalSinCupo     = Postulante::where('estado_final', 'Aprobado sin Cupo')->count();
        $totalGrupos      = Grupo::where('estado', true)->count();

        $evaluados = $totalAprobados + $totalReprobados;
        $tasaAprobacion = $evaluados > 0
            ? round(($totalAprobados / $evaluados) * 100, 1)
            : 0;

        return view('dashboard', compact(
            'totalInscritos',
            'totalHabilitados',
            'totalPendientes',
            'totalAprobados',
            'totalReprobados',
            'totalSinCupo',
            'totalGrupos',
            'tasaAprobacion'
        ));
    }
}
