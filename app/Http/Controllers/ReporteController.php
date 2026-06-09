<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Examen;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function desempeno(Request $request)
    {
        $grupos = Grupo::all();
        $materias = Materia::where('estado', true)->get();

        $grupoId = $request->filled('grupo_id') ? $request->input('grupo_id') : null;

        $estadisticas = [];

        foreach ($materias as $materia) {
            $examenesQuery = Examen::where('materia_id', $materia->id);

            if ($grupoId) {
                $examenesQuery->whereHas('postulante', function ($q) use ($grupoId) {
                    $q->where('grupo_id', $grupoId);
                });
            }

            $examenes = $examenesQuery->get();

            $totalEvaluados = $examenes->pluck('postulante_id')->unique()->count();
            $promedioGeneral = $examenes->avg('nota');
            $notaMaxima = $examenes->max('nota');
            $notaMinima = $examenes->min('nota');

            // Calcular aprobados: postulantes cuyo promedio en esta materia >= 60
            $aprobados = 0;
            if ($totalEvaluados > 0) {
                $promediosPorPostulante = $examenes->groupBy('postulante_id')
                    ->map(fn($exams) => $exams->avg('nota'));

                $aprobados = $promediosPorPostulante->filter(fn($p) => $p >= 60)->count();
            }

            $porcentajeAprobacion = $totalEvaluados > 0
                ? round(($aprobados / $totalEvaluados) * 100, 1)
                : 0;

            $estadisticas[] = [
                'materia'              => $materia,
                'total_evaluados'      => $totalEvaluados,
                'promedio_general'     => $promedioGeneral ?? 0,
                'nota_maxima'          => $notaMaxima ?? 0,
                'nota_minima'          => $notaMinima ?? 0,
                'aprobados'            => $aprobados,
                'reprobados'           => $totalEvaluados - $aprobados,
                'porcentaje_aprobacion'=> $porcentajeAprobacion,
            ];
        }

        // Ordenar por % de aprobación ascendente (materias más difíciles primero)
        usort($estadisticas, fn($a, $b) => $a['porcentaje_aprobacion'] <=> $b['porcentaje_aprobacion']);

        return view('reportes.desempeno', compact('estadisticas', 'grupos'));
    }
}
