<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AsignacionGrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    /**
     * Simulación: calcula grupos necesarios sin persistir.
     */
    public function index()
    {
        $sinGrupo = Postulante::where('estado_final', 'HABILITADO')
            ->whereNull('grupo_id')
            ->count();

        $gruposNecesarios = $sinGrupo > 0
            ? (int) ceil($sinGrupo / 70)
            : 0;

        $gruposExistentes = Grupo::where('estado', true)->count();
        $conGrupo = Postulante::where('estado_final', 'HABILITADO')
            ->whereNotNull('grupo_id')
            ->count();

        return view('grupos.asignacion', [
            'sinGrupo'          => $sinGrupo,
            'gruposNecesarios'  => $gruposNecesarios,
            'gruposExistentes'  => $gruposExistentes,
            'conGrupo'          => $conGrupo,
            'totalHabilitados'  => $sinGrupo + $conGrupo,
        ]);
    }

    /**
     * Ejecución definitiva: transacción ACID con persistencia.
     */
    public function ejecutar()
    {
        DB::beginTransaction();

        try {
            $postulantes = Postulante::where('estado_final', 'HABILITADO')
                ->whereNull('grupo_id')
                ->get();

            $gruposCreados = 0;
            $asignados = 0;
            $grupoActual = null;

            foreach ($postulantes as $postulante) {
                // Buscar grupo activo con cupo
                if (!$grupoActual || !$grupoActual->tieneCupo()) {
                    $grupoActual = Grupo::where('estado', true)
                        ->get()
                        ->first(fn($g) => $g->tieneCupo());

                    // Si no hay grupo con cupo, crear uno nuevo
                    if (!$grupoActual) {
                        $grupoActual = Grupo::create([
                            'nombre'           => 'Grupo Automático ' . Str::upper(Str::random(3)),
                            'codigo'           => 'AUTO-' . Str::upper(Str::random(4)),
                            'capacidad_maxima' => 70,
                            'estado'           => true,
                        ]);
                        $gruposCreados++;
                    }
                }

                $postulante->update(['grupo_id' => $grupoActual->id]);
                $asignados++;
            }

            DB::commit();

            Log::info('Asignación Automática de Grupos ejecutada', [
                'grupos_creados'  => $gruposCreados,
                'postulantes_asignados' => $asignados,
                'usuario_id'      => auth()->id(),
                'fecha'           => now()->toDateTimeString(),
            ]);

            return redirect()
                ->route('asignacion.index')
                ->with('success', "Asignación completada. {$gruposCreados} grupos nuevos creados. {$asignados} postulantes asignados.");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Asignación Automática falló', [
                'error'      => $e->getMessage(),
                'usuario_id' => auth()->id(),
            ]);

            return redirect()
                ->route('asignacion.index')
                ->with('error', 'Error al ejecutar la asignación. Se revirtieron todos los cambios.');
        }
    }
}
