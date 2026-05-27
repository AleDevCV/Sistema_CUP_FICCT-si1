@extends('layouts.app')

@section('title','Detalle Carrera')

@section('header','Detalle carrera')

@section('content')

<div class="card">

<h2>

{{ $carrera->nombre }}

</h2>

<br>

<p>

<strong>Código:</strong>

{{ $carrera->codigo }}

</p>

<br>

<p>

<strong>Cupo:</strong>

{{ $carrera->cupo }}

</p>

<br>

<p>

<strong>Gestión:</strong>

{{ $carrera->gestion }}

</p>

<br>

<p>

<strong>Estado:</strong>

{{ $carrera->estado ? 'Activo':'Inactivo' }}

</p>

<br>

<a
href="{{route('carreras.index')}}">

Volver

</a>

</div>

@endsection