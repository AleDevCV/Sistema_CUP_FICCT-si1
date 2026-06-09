<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CierreAcademicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    /**
     * Simulación: calcula promedios en memoria sin persistir.
     */
    public function index()
    {
        $postulantes = Postulante::with('examenes.materia')
            ->where('estado_final', 'HABILITADO')
            ->get();

        $materias = Materia::where('estado', true)->get();

        $resultados = [];
        $aprobados = 0;
        $reprobados = 0;

        foreach ($postulantes as $postulante) {
            $promedioFinalTotal = 0.0;

            foreach ($materias as $materia) {
                $examenesMateria = $postulante->examenes
                    ->where('materia_id', $materia->id);

                if ($examenesMateria->isEmpty()) {
                    // Sin exámenes en esta materia = promedio 0
                    continue;
                }

                $promedioMateria = $examenesMateria->avg('nota');
                $multiplicador = (float) $materia->ponderacion / 100.0;
                $aporteMateria = $promedioMateria * $multiplicador;
                $promedioFinalTotal += $aporteMateria;
            }

            $estado = $promedioFinalTotal >= 60 ? 'APROBADO' : 'REPROBADO';

            if ($estado === 'APROBADO') {
                $aprobados++;
            } else {
                $reprobados++;
            }
        }

        return view('cierre.index', [
            'totalHabilitados' => $postulantes->count(),
            'proyeccionAprobados' => $aprobados,
            'proyeccionReprobados' => $reprobados,
            'totalMaterias' => $materias->count(),
        ]);
    }

    /**
     * Ejecución definitiva: transacción ACID con persistencia.
     */
    public function ejecutar()
    {
        DB::beginTransaction();

        try {
            $postulantes = Postulante::with('examenes.materia')
                ->where('estado_final', 'HABILITADO')
                ->get();

            $materias = Materia::where('estado', true)->get();

            $aprobados = 0;
            $reprobados = 0;

            foreach ($postulantes as $postulante) {
                $promedioFinalTotal = 0.0;

                foreach ($materias as $materia) {
                    $examenesMateria = $postulante->examenes
                        ->where('materia_id', $materia->id);

                    if ($examenesMateria->isEmpty()) {
                        // Sin exámenes en esta materia = promedio 0
                        continue;
                    }

                    $promedioMateria = $examenesMateria->avg('nota');
                    $multiplicador = (float) $materia->ponderacion / 100.0;
                    $aporteMateria = $promedioMateria * $multiplicador;
                    $promedioFinalTotal += $aporteMateria;
                }

                $estado = $promedioFinalTotal >= 60 ? 'APROBADO' : 'REPROBADO';

                $postulante->update([
                    'promedio_final' => round($promedioFinalTotal, 2),
                    'estado_final' => $estado,
                ]);

                if ($estado === 'APROBADO') {
                    $aprobados++;
                } else {
                    $reprobados++;
                }
            }

            DB::commit();

            Log::info('Cierre Académico ejecutado', [
                'total_habilitados' => $postulantes->count(),
                'aprobados' => $aprobados,
                'reprobados' => $reprobados,
                'usuario_id' => auth()->id(),
                'fecha' => now()->toDateTimeString(),
            ]);

            return redirect()
                ->route('cierre.index')
                ->with('success', "Cierre académico ejecutado. {$aprobados} aprobados, {$reprobados} reprobados.");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Cierre Académico falló', [
                'error' => $e->getMessage(),
                'usuario_id' => auth()->id(),
            ]);

            return redirect()
                ->route('cierre.index')
                ->with('error', 'Error al ejecutar el cierre académico. Se revirtieron todos los cambios.');
        }
    }
}
