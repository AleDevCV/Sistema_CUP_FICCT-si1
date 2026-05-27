@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <h3 class="mb-0">
                Detalle Usuario
            </h3>

            <a
                href="{{ route('users.index') }}"
                class="btn btn-secondary"
            >
                Volver
            </a>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <strong>ID:</strong>

                </div>

                <div class="col-md-8">

                    {{ $user->id }}

                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4">

                    <strong>Nombre:</strong>

                </div>

                <div class="col-md-8">

                    {{ $user->name }}

                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4">

                    <strong>Username:</strong>

                </div>

                <div class="col-md-8">

                    {{ $user->username }}

                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4">

                    <strong>Email:</strong>

                </div>

                <div class="col-md-8">

                    {{ $user->email }}

                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4">

                    <strong>Rol:</strong>

                </div>

                <div class="col-md-8">

                    {{ $user->role->name ?? '-' }}

                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4">

                    <strong>Estado:</strong>

                </div>

                <div class="col-md-8">

                    @if($user->status)

                        <span class="badge bg-success">
                            Activo
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Inactivo
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection