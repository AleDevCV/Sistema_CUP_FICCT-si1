<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Importacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PostulanteController extends Controller
{
    /*
    Mostrar listado
    */

    public function index()
    {
        $postulantes = Postulante::with([

            'primeraCarrera',
            'segundaCarrera',
            'grupo'

        ])
        ->latest()
        ->paginate(10);

        return view(
            'postulantes.index',
            compact('postulantes')
        );
    }


    /*
    Mostrar formulario
    */

    public function create()
    {
        $carreras = Carrera::where(
            'estado',
            true
        )->get();

        $colegios = $this->getColegios();

        return view(
            'postulantes.create',
            compact(
                'carreras',
                'colegios'
            )
        );
    }


    /*
    Guardar
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'ci' => 'required|unique:postulantes,ci',
            'nombres' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:users,email',
            'colegio' => 'required',
            'ciudad' => 'required',
            'titulo_bachiller' => 'accepted',

            'carrera_primera_opcion_id' =>
            'required|exists:carreras,id',

            'carrera_segunda_opcion_id' =>
            'nullable|exists:carreras,id|different:carrera_primera_opcion_id',

        ]);

        // Crear cuenta de usuario automática
        $user = User::create([
            'name'     => $request->nombres . ' ' . $request->apellidos,
            'username' => $request->ci,
            'email'    => $request->email,
            'password' => bcrypt($request->ci),
        ]);
        $user->assignRole('Postulante');

        // Crear postulante con campos seguros + forzados
        Postulante::create([
            'user_id'                   => $user->id,
            'ci'                        => $request->ci,
            'nombres'                   => $request->nombres,
            'apellidos'                 => $request->apellidos,
            'fecha_nacimiento'          => $request->fecha_nacimiento,
            'sexo'                      => $request->sexo,
            'direccion'                 => $request->direccion,
            'telefono'                  => $request->telefono,
            'email'                     => $request->email,
            'colegio'                   => $request->colegio,
            'ciudad'                    => $request->ciudad,
            'titulo_bachiller'          => true,
            'carrera_primera_opcion_id' => $request->carrera_primera_opcion_id,
            'carrera_segunda_opcion_id' => $request->carrera_segunda_opcion_id,
            'estado'                    => true,
            'estado_final'              => 'PENDIENTE',
        ]);

        return redirect()
            ->route(
                'postulantes.index'
            )
            ->with(
                'success',
                'Postulante creado correctamente'
            );
    }


    /*
    Ver detalle
    */

    public function show(
        Postulante $postulante
    )
    {
        $postulante->load([

            'primeraCarrera',
            'segundaCarrera',
            'grupo',
            'pago',
            'examenes'

        ]);

        return view(
            'postulantes.show',
            compact(
                'postulante'
            )
        );
    }


    /*
    Editar
    */

    public function edit(
        Postulante $postulante
    )
    {
        $carreras = Carrera::all();

        $colegios = $this->getColegios();

        return view(
            'postulantes.edit',
            compact(
                'postulante',
                'carreras',
                'colegios'
            )
        );
    }


    /*
    Actualizar
    */

    public function update(
        Request $request,
        Postulante $postulante
    )
    {
        $data = $request->validate([

            'ci' =>
            'required|unique:postulantes,ci,' .
            $postulante->id,

            'nombres' =>
            'required|max:255',

            'apellidos' =>
            'required|max:255',

            'fecha_nacimiento' =>
            'required|date',

            'sexo' =>
            'required',

            'direccion' =>
            'required',

            'telefono' =>
            'required',

            'email' =>
            'nullable|email',

            'colegio' =>
            'required',

            'ciudad' =>
            'required',

            'titulo_bachiller' =>
            'required|max:255',

            'otros_requisitos' =>
            'nullable',

            'carrera_primera_opcion_id' =>
            'required|exists:carreras,id',

            'carrera_segunda_opcion_id' =>
            'nullable|exists:carreras,id|different:carrera_primera_opcion_id',

            'grupo_id' =>
            'nullable|exists:grupos,id',

            'promedio_final' =>
            'nullable|numeric',

            'estado_final' =>
            'nullable|max:100',

            'estado' =>
            'required|boolean'

        ]);

        $postulante->update(
            $data
        );

        return redirect()
            ->route(
                'postulantes.index'
            )
            ->with(
                'success',
                'Postulante actualizado correctamente'
            );
    }


    /*
    Eliminar
    */

    public function destroy(
        Postulante $postulante
    )
    {
        $postulante->delete();

        return redirect()
            ->route(
                'postulantes.index'
            )
            ->with(
                'success',
                'Postulante eliminado correctamente'
            );
    }

    /**
     * Importación masiva de postulantes vía CSV.
     */
    public function importarCsv(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:csv,txt',
        ]);

        $archivo = $request->file('archivo');
        $importacion = Importacion::create([
            'nombre_archivo' => $archivo->getClientOriginalName(),
        ]);

        DB::transaction(function () use ($archivo, $importacion) {
            $handle = fopen($archivo->getRealPath(), 'r');
            fgetcsv($handle); // saltar cabecera

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 12) continue;

                [
                    $ci, $nombres, $apellidos, $fecha_nacimiento, $sexo,
                    $direccion, $telefono, $email, $colegio, $ciudad,
                    $carrera_primera_opcion_id, $carrera_segunda_opcion_id
                ] = $row;

                if (User::where('email', $email)->exists() || Postulante::where('ci', $ci)->exists()) {
                    continue;
                }

                if ($carrera_primera_opcion_id == $carrera_segunda_opcion_id) {
                    continue;
                }

                $user = User::create([
                    'name'     => $nombres . ' ' . $apellidos,
                    'username' => $ci,
                    'email'    => $email,
                    'password' => bcrypt($ci),
                ]);
                Role::findOrCreate('Postulante');
                $user->assignRole('Postulante');

                Postulante::create([
                    'user_id'                   => $user->id,
                    'importacion_id'            => $importacion->id,
                    'ci'                        => $ci,
                    'nombres'                   => $nombres,
                    'apellidos'                 => $apellidos,
                    'fecha_nacimiento'          => $fecha_nacimiento,
                    'sexo'                      => $sexo,
                    'direccion'                 => $direccion,
                    'telefono'                  => $telefono,
                    'email'                     => $email,
                    'colegio'                   => $colegio,
                    'ciudad'                    => $ciudad,
                    'titulo_bachiller'          => true,
                    'carrera_primera_opcion_id' => $carrera_primera_opcion_id,
                    'carrera_segunda_opcion_id' => $carrera_segunda_opcion_id ?: null,
                    'estado'                    => true,
                    'estado_final'              => 'PENDIENTE',
                ]);
            }

            fclose($handle);
        });

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Importación masiva completada.');
    }

    /**
     * Historial de importaciones.
     */
    public function historialImportaciones()
    {
        $importaciones = Importacion::withCount('postulantes')->latest()->get();

        return view('postulantes.historial', compact('importaciones'));
    }

    /**
     * Revertir importación y eliminar usuarios/postulantes asociados.
     */
    public function revertirImportacion(Importacion $importacion)
    {
        $userIds = $importacion->postulantes()->pluck('user_id');

        User::whereIn('id', $userIds)->delete(); // cascade elimina postulantes

        $importacion->update(['revertida' => true]);

        return redirect()
            ->route('postulantes.historial')
            ->with('success', 'Importación revertida correctamente.');
    }

    private function getColegios()
    {
        $colegios = ['8 DE SEPTIEMBRE', 'DEMETRIO GOMEZ', 'BICENTENARIO ARCO IRIS', 'DR. HERNANDO SANABRIA FERNANDEZ', 'ISAAC GUTIERREZ CRUZ', 'JOSE MARIANO SERRANO', 'JOSE VICENTE SOLIZ Y RAMOS', 'JOSE VILLARROEL ROBLES', 'MAESTRO PITAGORAS II', 'MARCELO QUIROGA SANTA CRUZ', 'MARIA AYMA MAMANI', 'METROPOLITANA II', 'NACIONAL DE LA GUARDIA II', 'NACIONAL SAN JOSE', 'NACIONAL SANTA CRUZ', 'NUEVA AMERICA', 'NUEVA ESPERANZA', 'PAULO FREIRE II', 'PEDRO LORENZO I', 'SAN MIGUEL DEL ROSARIO', '26 DE FEBRERO', '2 DE MARZO', 'ALBERTO RIVERA TAPIA', 'BICENTENARIO', 'CAÑADA DOS', 'JOHN ANDREWS', 'LA SANTA CRUZ', 'LOUISA STOLTENBERC', 'NACIONAL ANDRES IBAÑEZ', 'NACIONAL EL TORNO TARDE', 'NACIONAL JOROCHITO', 'NACIONES UNIDAS MAÑANA', 'NEMESIO SOLIZ GARRIDO', 'SAN JUAN', 'TIQUIPAYA', 'TOMAS FERNANDEZ', 'ANGELMIRA MONTENEGRO DE DALENCE', 'AVELINO SIÑANI', 'BOLIVARIANA JUANCITO PINTO', 'BUENA FE', 'CAMPO ROSSO 4 DE ABRIL', 'CONSTANTINO CUELLAR', 'EL BARRIAL', 'EL MANANTIAL', 'EL TAJIBO', 'ESPAÑA', 'EUGENIA RAVASCO', 'GRAN PAITITI', 'IGNACIO WARNES', 'INTEGRACION DEL NORTE', 'JOHN FITZGERALD KENNEDY', 'JOSE MANUEL VACA', 'JUANA AZURDUY DE PADILLA', 'LAS BARRERAS', 'LUZ Y VERDAD', 'POTRERO LARGO', 'SAN ANTONIO', 'SATELITE NORTE', 'TERRACOR', 'DON BOSCO', '6 DE AGOSTO', 'CARANDA', 'EDUARDO ABAROA', 'ANTOFAGASTA', 'BOLIVIANO ITALIANO', 'CORAZON NUEVO', 'VILLA IMPERIAL', '25 DE MAYO', 'FRANZ TAMAYO', 'GERMAN BUSCH', 'HEROINAS', 'MCAL. SUCRE', 'NUEVO HORIZONTE', 'SIMON BOLIVAR I', 'FELIPE LEONOR RIBERA', 'MARISTA SECUNDARIA', 'OSCAR UNZAGA DE LA VEGA', 'SAN JUAN BAUTISTA', 'SAN JOSE', 'CRISTIANA PORTACHUELO', 'SERGIO VACA VELASCO', '4 DE MARZO', 'ALFREDO VACAFLOR', 'LOS ANDES', 'SAN LUIS', '15 DE MAYO', '21 DE MAYO', '2 DE ABRIL', '10 DE SEPTIEMBRE', '12 DE ABRIL', 'NUESTRA SEÑORA DEL CARMEN', 'EL ARENAL', 'SANTA CLARA', 'CAMIRI', 'GABRIEL RENE MORENO', 'JESUS NAZARENO', 'JUAN PABLO II', 'NIÑO JESUS', 'SIMON BOLIVAR', 'RUBEN TERRAZAS', 'EMILIO FINOT', 'JOSE AGUIRRE ACHA', 'FLORIDA', 'LIBERTAD', 'MARIA AUXILIADORA A', 'NUEVO MILENIO', '15 DE AGOSTO', 'BOLIVIA', 'INDEPENDENCIA', '12 DE JUNIO', '1RO. DE MAYO', '21 DE JUNIO', 'SAN PEDRO', '16 DE MARZO', '27 DE ABRIL', '2 DE AGOSTO', 'EL PROGRESO', 'LA ASUNTA', 'PATUJU', 'SAN JUAN DE BERLIN', 'SIBERIA', 'TORRECILLAS', 'SANTA CATALINA', 'SANTA ROSA', '27 DE MAYO', 'LA FRONTERA', 'SAN AGUSTIN', 'SANTA CRUZ', '16 DE JULIO', '5 DE MARZO', 'RESIDENCIAL', '10 DE NOVIEMBRE', 'ALEMAN', 'BELLAS ARTES', 'DOMINGO SAVIO A', 'EAGLES', 'FRANCO BOLIVIANO', 'JOVEN BOLIVIA', 'PENIEL', 'SANTA ROSA DE LIMA', 'CARITAS I', 'CASTULO CHAVEZ', 'HERMANN GMEINER', 'EL RETOÑO', 'LOS ANGELES', 'SAN FRANCISCO', 'MODELO OKINAWA', 'NUEVA ANDALUCIA', 'PUERTO NUEVO', 'SAN MIGUEL', 'LA ENCONADA', 'SAN JOSE OBRERO', 'PADRE PIO', 'SAN SEBASTIAN', 'VILLA CHAVEZ', 'HOLANDA', 'PALESTINA', 'PERLA DEL ORIENTE', 'SAGRADO CORAZON DE JESUS', 'SAN MIGUEL FLORIDA', 'OTROS...'];
        sort($colegios);
        return $colegios;
    }
}