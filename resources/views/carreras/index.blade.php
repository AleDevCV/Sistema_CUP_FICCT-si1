@extends('layouts.app')

@section('title','Carreras')

@section('header','Gestión de Carreras')

@section('content')

<div class="card">

    <div style="display:flex;justify-content:space-between;margin-bottom:20px;">

        <h2>Lista de carreras</h2>

        <a
        href="{{ route('carreras.create') }}"
        style="
        background:#2563eb;
        color:white;
        padding:10px 15px;
        text-decoration:none;
        border-radius:8px;">

            Nueva carrera

        </a>

    </div>

    @if(session('success'))

        <div
        style="
        background:#dcfce7;
        color:#166534;
        padding:10px;
        border-radius:8px;
        margin-bottom:15px;">

            {{ session('success') }}

        </div>

    @endif

    <table
    width="100%"
    border="0"
    cellpadding="10">

        <thead>

            <tr style="background:#f1f5f9">

                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cupo</th>
                <th>Gestión</th>
                <th>Estado</th>
                <th>Acciones</th>

            </tr>

        </thead>

        <tbody>

        @forelse($carreras as $carrera)

            <tr>

                <td>{{ $carrera->id }}</td>
                <td>{{ $carrera->codigo }}</td>
                <td>{{ $carrera->nombre }}</td>
                <td>{{ $carrera->cupo }}</td>
                <td>{{ $carrera->gestion }}</td>

                <td>

                    @if($carrera->estado)

                        Activo

                    @else

                        Inactivo

                    @endif

                </td>

                <td>

                    <a
                    href="{{route('carreras.show',$carrera)}}">

                        Ver

                    </a>

                    |

                    <a
                    href="{{route('carreras.edit',$carrera)}}">

                        Editar

                    </a>

                    |

                    <form
                    action="{{route('carreras.destroy',$carrera)}}"
                    method="POST"
                    style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                        onclick="return confirm('¿Eliminar carrera?')">

                            Eliminar

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7">

                    No existen carreras

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

    <br>

    {{ $carreras->links() }}

</div>

@endsection