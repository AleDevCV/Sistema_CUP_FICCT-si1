<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostulantePanelController extends Controller
{
    public function index()
    {
        $postulante = auth()->user()->postulante;

        if (!$postulante) {
            abort(403, 'No tienes un perfil de postulante asociado.');
        }

        $postulante->load([
            'grupo',
            'carreraAdmitida',
            'examenes.materia',
        ]);

        return view('postulantes.panel', compact('postulante'));
    }
}
