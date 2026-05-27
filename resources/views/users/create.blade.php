@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">

            <h3 class="mb-0">
                Crear Usuario
            </h3>

        </div>

        <div class="card-body">

            <form
                action="{{ route('users.store') }}"
                method="POST"
            >

                @csrf

                @include('users.form')

            </form>

        </div>

    </div>

</div>

@endsection