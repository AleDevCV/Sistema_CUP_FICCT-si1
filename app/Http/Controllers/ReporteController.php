<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Postulante;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $grupos = Grupo::all();
        $carreras = Carrera::all();

        $query = Postulante::with(['grupo', 'carreraAdmitida']);

        if ($request->filled('estado')) {
            $query->where('estado_final', $request->input('estado'));
        }

        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->input('grupo_id'));
        }

        if ($request->filled('carrera_id')) {
            $query->where('carrera_admitida_id', $request->input('carrera_id'));
        }

        $postulantes = $query->orderByDesc('promedio_final')->get();

        return view('reportes.general', compact(
            'postulantes',
            'grupos',
            'carreras'
        ));
    }
}
