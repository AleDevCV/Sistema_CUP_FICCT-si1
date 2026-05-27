@extends('layouts.app')

@section('title','Detalle Grupo')

@section('header','Detalle Grupo')

@section('content')

<div class="card">

<h2>

{{ $grupo->nombre }}

</h2>

<br>

<p>

<strong>Código:</strong>

{{ $grupo->codigo }}

</p>

<p>

<strong>Aula:</strong>

{{ $grupo->aula }}

</p>

<p>

<strong>Horario:</strong>

{{ $grupo->horario }}

</p>

<p>

<strong>Capacidad:</strong>

{{ $grupo->totalAlumnos() }}

/

{{ $grupo->capacidad_maxima }}

</p>

<p>

<strong>Tiene cupo:</strong>

{{ $grupo->tieneCupo() ? 'Sí':'No' }}

</p>

<p>

<strong>Estado:</strong>

{{ $grupo->estado ? 'Activo':'Inactivo' }}

</p>

<br>

<h3>

Docentes asignados

</h3>

<ul>

@forelse($grupo->grupoDocentes as $asignacion)

<li>

{{ $asignacion->docente->nombre_completo }}

-

{{ $asignacion->materia->nombre }}

</li>

@empty

<li>

Sin asignaciones

</li>

@endforelse

</ul>

<br>

<a href="{{ route('grupos.index') }}">

Volver

</a>

</div>

@endsection