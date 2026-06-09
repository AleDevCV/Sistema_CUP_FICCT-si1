<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReasignacionCuposController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    /**
     * Simulación: estado de cupos por carrera sin persistir.
     */
    public function index()
    {
        $carreras = Carrera::withCount([
            'postulantesPrimeraOpcion as aprobados_count' => function ($q) {
                $q->where('estado_final', 'APROBADO');
            },
        ])->get();

        $totalAprobados = Postulante::where('estado_final', 'APROBADO')->count();
        $totalCupos = Carrera::sum('cupo');
        $excedentesEstimados = max(0, $totalAprobados - $totalCupos);

        return view('cierre.reasignacion', [
            'carreras'             => $carreras,
            'totalAprobados'       => $totalAprobados,
            'totalCupos'           => $totalCupos,
            'excedentesEstimados'  => $excedentesEstimados,
        ]);
    }

    /**
     * Ejecución definitiva: asignación por mérito con failover.
     */
    public function ejecutar()
    {
        DB::beginTransaction();

        try {
            // Cargar cupos disponibles por carrera
            $carreras = Carrera::all();
            $cupos = [];
            foreach ($carreras as $carrera) {
                $cupos[$carrera->id] = (int) $carrera->cupo;
            }

            // Postulantes APROBADOS ordenados por mérito
            $postulantes = Postulante::where('estado_final', 'APROBADO')
                ->orderBy('promedio_final', 'desc')
                ->orderBy('id', 'asc')
                ->get();

            $primerasOpciones = 0;
            $reasignados = 0;
            $sinCupo = 0;

            foreach ($postulantes as $postulante) {
                $primera = $postulante->carrera_primera_opcion_id;
                $segunda = $postulante->carrera_segunda_opcion_id;

                // Intentar primera opción
                if ($cupos[$primera] > 0) {
                    $postulante->update([
                        'carrera_admitida_id' => $primera,
                    ]);
                    $cupos[$primera]--;
                    $primerasOpciones++;
                }
                // Failover: segunda opción
                elseif ($segunda && isset($cupos[$segunda]) && $cupos[$segunda] > 0) {
                    $postulante->update([
                        'carrera_admitida_id' => $segunda,
                    ]);
                    $cupos[$segunda]--;
                    $reasignados++;
                }
                // Emergencia: ambas llenas
                else {
                    $postulante->update([
                        'estado_final' => 'Aprobado sin Cupo',
                    ]);
                    $sinCupo++;
                }
            }

            DB::commit();

            Log::info('Reasignación de Cupos ejecutada', [
                'primera_opcion'        => $primerasOpciones,
                'reasignados_segunda'   => $reasignados,
                'sin_cupo'              => $sinCupo,
                'total_aprobados'       => $postulantes->count(),
                'usuario_id'            => auth()->id(),
                'fecha'                 => now()->toDateTimeString(),
            ]);

            return redirect()
                ->route('reasignacion.index')
                ->with('success', "Reasignación completada. {$primerasOpciones} en 1ra opción, {$reasignados} reasignados a 2da opción, {$sinCupo} sin cupo.");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Reasignación de Cupos falló', [
                'error'      => $e->getMessage(),
                'usuario_id' => auth()->id(),
            ]);

            return redirect()
                ->route('reasignacion.index')
                ->with('error', 'Error al ejecutar la reasignación. Se revirtieron todos los cambios.');
        }
    }
}
