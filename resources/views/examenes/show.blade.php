@extends('layouts.app')

@section('title','Detalle Examen')

@section('header','Detalle Examen')

@section('content')

<div class="card">

<h2>

Examen #{{ $examen->numero_examen }}

</h2>

<br>

<p>

<strong>Postulante:</strong>

{{ $examen->postulante?->nombre_completo ?? 'Sin postulante' }}

</p>

<br>

<p>

<strong>Materia:</strong>

{{ $examen->materia?->nombre ?? 'Sin materia' }}

</p>

<br>

<p>

<strong>Nota:</strong>

{{ $examen->nota }}

</p>

<br>

<p>

<strong>Porcentaje:</strong>

{{ $examen->porcentaje }}%

</p>

<br>

<a
href="{{ route('examenes.index') }}">

Volver

</a>

</div>

@endsection