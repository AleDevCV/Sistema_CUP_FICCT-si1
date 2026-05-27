@extends('layouts.app')

@section('title','Detalle Postulante')

@section('header','Detalle Postulante')

@section('content')

<div class="card">

<h2>

{{ $postulante->nombre_completo }}

</h2>

<br>

<p>
<strong>CI:</strong>
{{ $postulante->ci }}
</p>

<p>
<strong>Email:</strong>
{{ $postulante->email }}
</p>

<p>
<strong>Teléfono:</strong>
{{ $postulante->telefono }}
</p>

<p>
<strong>Colegio:</strong>
{{ $postulante->colegio }}
</p>

<p>
<strong>Primera carrera:</strong>

{{ $postulante->primeraCarrera?->nombre }}

</p>

<p>
<strong>Segunda carrera:</strong>

{{ $postulante->segundaCarrera?->nombre }}

</p>

<p>
<strong>Grupo:</strong>

{{ $postulante->grupo?->nombre }}

</p>

<br>

<a
href="{{ route('postulantes.index') }}">

Volver

</a>

</div>

@endsection