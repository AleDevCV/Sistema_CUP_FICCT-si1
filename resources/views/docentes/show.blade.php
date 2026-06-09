@extends('layouts.app')

@section('title','Detalle Docente')

@section('header','Detalle Docente')

@section('content')

<div class="card">

<h2>

{{ $docente->nombre_completo }}

</h2>

<br>

<p>

<strong>CI:</strong>
{{ $docente->ci }}

</p>

<p>

<strong>Email:</strong>
{{ $docente->user?->email }}

</p>

<p>

<strong>Teléfono:</strong>
{{ $docente->telefono }}

</p>

<p>

<strong>Maestría:</strong>
{{ $docente->maestria ? 'Sí':'No' }}

</p>

<p>

<strong>Diplomado:</strong>
{{ $docente->diplomado_educacion_superior ? 'Sí':'No' }}

</p>

<p>

<strong>Puede contratarse:</strong>

{{ $docente->puedeContratarse() ? 'Sí':'No' }}

</p>

<br>

<a href="{{ route('docentes.index') }}">

Volver

</a>

</div>

@endsection