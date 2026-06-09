<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

/**
 * Controlador de Auditoría/Bitácora — CU10.
 * Solo accesible por Administrador.
 */
class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Auditoria::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('modelo')) {
            $query->where('modelo', $request->modelo);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        $auditorias = $query->paginate(15);

        return view('auditorias.index', compact('auditorias'));
    }
}
