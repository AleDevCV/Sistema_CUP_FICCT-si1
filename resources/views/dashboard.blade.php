@extends('layouts.app')

@section('title','Dashboard')

@section('header','Panel principal')

@section('content')

<div class="card">

    <h2>

        Bienvenido {{ auth()->user()->name }}

    </h2>

    <br>

    <p>

        Has iniciado sesión correctamente.

    </p>

    <br>

    <form
    method="POST"
    action="{{ route('logout') }}">

        @csrf

        <button
        class="logout-btn">

            Cerrar sesión

        </button>

    </form>

</div>

@endsection