@extends('layouts.app')

@section('title','Materias')

@section('header','Gestión de Materias')

@section('content')

<div class="card">

<div
style="
display:flex;
justify-content:space-between;
margin-bottom:20px;">

<h2>

Lista de Materias

</h2>

<a
href="{{ route('materias.create') }}"
style="
padding:10px 15px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nueva Materia

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


@if(session('error'))

<div style="
background:#fee2e2;
color:#991b1b;
padding:10px;
margin-bottom:15px;
border-radius:8px;">

{{ session('error') }}

</div>

@endif


<table
width="100%"
cellpadding="10">

<thead>

<tr style="background:#f1f5f9">

<th>ID</th>
<th>Nombre</th>
<th>Estado</th>
<th>Exámenes</th>
<th>Grupo Docente</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@forelse($materias as $materia)

<tr>

<td>

{{ $materia->id }}

</td>

<td>

{{ $materia->nombre }}

</td>

<td>

{{ $materia->estado ? 'Activo':'Inactivo' }}

</td>

<td>

{{ $materia->examenes_count }}

</td>

<td>

{{ $materia->grupo_docentes_count }}

</td>

<td>

<a
href="{{ route('materias.show',$materia) }}">

Ver

</a>

|

<a
href="{{ route('materias.edit',$materia) }}">

Editar

</a>

|

<form
method="POST"
action="{{ route('materias.destroy',$materia) }}"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('¿Eliminar materia?')">

Eliminar

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="6">

No existen materias

</td>

</tr>

@endforelse

</tbody>

</table>

<br>

{{ $materias->links() }}

</div>

@endsection