@extends('layouts.app')

@section('title','Detalle Examen')

@section('header','Detalle Examen')

@section('content')
<pre>
{{ print_r($examen->toArray(), true) }}
</pre>
<pre>
{{ print_r($examen->postulante, true) }}
</pre>
<div class="card">

<h2>

Examen #{{ $examen->numero_examen }}

</h2>

<br>

<p>

<strong>Postulante:</strong>

{{ $examen->postulante->nombre_completo }}

</p>

<br>

<p>

<strong>Materia:</strong>

{{ $examen->materia->nombre }}

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

<p>

<strong>Nota Final:</strong>

{{ number_format($examen->notaFinal(),2) }}

</p>

<br>

<a
href="{{ route('examenes.index') }}">

Volver

</a>

</div>

@endsection