@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">
            Usuarios
        </h2>

        <a
            href="{{ route('users.create') }}"
            class="btn btn-primary"
        >
            Nuevo Usuario
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card">

        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th width="250">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>
                                {{ $user->id }}
                            </td>

                            <td>
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->username }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                {{ $user->role->name ?? '-' }}
                            </td>

                            <td>

                                @if($user->status)

                                    <span class="badge bg-success">
                                        Activo
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactivo
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route('users.show',$user) }}"
                                    class="btn btn-info btn-sm"
                                >
                                    Ver
                                </a>

                                <a
                                    href="{{ route('users.edit',$user) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    Editar
                                </a>

                                <form
                                    action="{{ route('users.destroy',$user) }}"
                                    method="POST"
                                    class="d-inline"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar usuario?')"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center"
                            >
                                No hay usuarios registrados
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-3">

        {{ $users->links() }}

    </div>

</div>

@endsection