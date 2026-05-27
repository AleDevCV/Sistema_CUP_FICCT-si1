@extends('layouts.app')

@section('title','Roles')

@section('header','Gestión de Roles')

@section('content')

<div class="card">

    <div style="display:flex;justify-content:space-between;margin-bottom:20px;">

        <h2>Lista de roles</h2>

        <a
        href="{{ route('roles.create') }}"
        style="
        background:#2563eb;
        color:white;
        padding:10px 15px;
        border-radius:8px;
        text-decoration:none;">

            Nuevo rol

        </a>

    </div>

    @if(session('success'))

        <div
        style="
        background:#dcfce7;
        color:#166534;
        padding:10px;
        margin-bottom:15px;
        border-radius:8px;">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div
        style="
        background:#fee2e2;
        color:#991b1b;
        padding:10px;
        margin-bottom:15px;
        border-radius:8px;">

            {{ session('error') }}

        </div>

    @endif


    <table
    width="100%"
    cellpadding="10">

        <thead>

        <tr style="background:#f1f5f9">

            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Usuarios</th>
            <th>Acciones</th>

        </tr>

        </thead>

        <tbody>

        @forelse($roles as $role)

        <tr>

            <td>

                {{ $role->id }}

            </td>

            <td>

                {{ $role->name }}

            </td>

            <td>

                {{ $role->description }}

            </td>

            <td>

                {{ $role->users_count }}

            </td>

            <td>

                <a
                href="{{ route('roles.show',$role) }}">

                    Ver

                </a>

                |

                <a
                href="{{ route('roles.edit',$role) }}">

                    Editar

                </a>

                |

                <form
                action="{{ route('roles.destroy',$role) }}"
                method="POST"
                style="display:inline">

                    @csrf
                    @method('DELETE')

                    <button
                    onclick="return confirm('¿Eliminar rol?')">

                        Eliminar

                    </button>

                </form>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="5">

                No existen roles

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

    <br>

    {{ $roles->links() }}

</div>

@endsection