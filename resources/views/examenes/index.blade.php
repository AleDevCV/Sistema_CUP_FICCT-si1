@extends('layouts.app')

@section('title','Exámenes')

@section('header','Gestión de Exámenes — Postulantes')

@section('content')

<div class="card">

<div style="
display:flex;
justify-content:space-between;
margin-bottom:20px;">

<h2>

Postulantes con Exámenes

</h2>

@hasanyrole('Administrador|Coordinador')
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

<form
method="POST"
action="{{ route('examenes.generar') }}"
style="display:inline;"
onsubmit="return confirm('¿Generar notas aleatorias para TODOS los postulantes habilitados?')">

@csrf

<button
style="
padding:10px 15px;
background:#7c3aed;
color:white;
border:none;
border-radius:8px;
cursor:pointer;">

🎲 Notas Aleatorias

</button>

</form>

<form
method="POST"
action="{{ route('examenes.vaciar') }}"
style="display:inline;"
onsubmit="return confirm('¿Eliminar TODOS los exámenes? Esta acción no se puede deshacer.')">

@csrf
@method('DELETE')

<button
style="
padding:10px 15px;
background:#dc2626;
color:white;
border:none;
border-radius:8px;
cursor:pointer;">

🗑 Eliminar Todo

</button>

</form>
@endhasanyrole

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

<th>CI</th>
<th>Nombre del Postulante</th>
<th>Exámenes Registrados</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@forelse($postulantes as $postulante)

<tr>

<td>

{{ $postulante->ci }}

</td>

<td>

{{ $postulante->nombre_completo }}

</td>

<td>

{{ $postulante->total_examenes }}

</td>

<td>

<a
href="{{ route('examenes.postulante', $postulante) }}"
style="
padding:6px 12px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:6px;
display:inline-block;">

Ver Materias/Notas

</a>

</td>

</tr>

@empty

<tr>

<td colspan="4">

No existen postulantes con exámenes registrados

</td>

</tr>

@endforelse

</tbody>

</table>

<br>

{{ $postulantes->links() }}

</div>

@endsection