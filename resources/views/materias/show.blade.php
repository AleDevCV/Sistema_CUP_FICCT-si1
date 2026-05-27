@extends('layouts.app')

@section('title','Detalle Materia')

@section('header','Detalle Materia')

@section('content')

<div class="card">

<h2>

{{ $materia->nombre }}

</h2>

<br>

<p>

<strong>Descripción:</strong>

{{ $materia->descripcion ?? 'Sin descripción' }}

</p>

<br>

<p>

<strong>Estado:</strong>

{{ $materia->estado ? 'Activo':'Inactivo' }}

</p>

<br>

<p>

<strong>Total Exámenes:</strong>

{{ $materia->examenes->count() }}

</p>

<br>

<p>

<strong>Total Grupo Docentes:</strong>

{{ $materia->grupoDocentes->count() }}

</p>

<br>

<a
href="{{ route('materias.index') }}">

Volver

</a>

</div>

@endsection