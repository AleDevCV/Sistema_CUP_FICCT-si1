@extends('layouts.app')

@section('title','Exámenes')

@section('header','Gestión de Exámenes')

@section('content')

<div class="card">

<div style="
display:flex;
justify-content:space-between;
margin-bottom:20px;">

<h2>

Lista de Exámenes

</h2>

<a
href="{{ route('examenes.create') }}"
style="
padding:10px 15px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nuevo Examen

</a>

</div>


@if(session('success'))

<div style="
background:#dcfce7;
color:#166534;
padding:10px;
margin-bottom:15px;
border-radius:8px;">

{{ session('success') }}

</div>

@endif


<table
width="100%"
cellpadding="10">

<thead>

<tr style="background:#f1f5f9">

<th>ID</th>
<th>Postulante</th>
<th>Materia</th>
<th>Examen</th>
<th>Nota</th>
<th>%</th>
<th>Nota Final</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@forelse($examenes as $examen)

<tr>

<td>

{{ $examen->id }}

</td>

<td>

{{ $examen->postulante->nombre_completo }}

</td>

<td>

{{ $examen->materia->nombre }}

</td>

<td>

{{ $examen->numero_examen }}

</td>

<td>

{{ $examen->nota }}

</td>

<td>

{{ $examen->porcentaje }}%

</td>

<td>

{{ number_format($examen->notaFinal(),2) }}

</td>

<td>

<a href="{{ route('examenes.show',$examen) }}">

Ver

</a>

|

<a href="{{ route('examenes.edit',$examen) }}">

Editar

</a>

|

<form
method="POST"
action="{{ route('examenes.destroy',$examen) }}"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('¿Eliminar examen?')">

Eliminar

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="8">

No existen exámenes

</td>

</tr>

@endforelse

</tbody>

</table>

<br>

{{ $examenes->links() }}

</div>

@endsection