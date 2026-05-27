@extends('layouts.app')

@section('title','Grupos')

@section('header','Gestión de Grupos')

@section('content')

<div class="card">

<div style="
display:flex;
justify-content:space-between;
margin-bottom:20px;">

<h2>

Lista de Grupos

</h2>

<a
href="{{ route('grupos.create') }}"
style="
padding:10px 15px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nuevo Grupo

</a>

</div>


@if(session('success'))

<div style="
background:#dcfce7;
padding:10px;
margin-bottom:15px;
border-radius:8px;">

{{ session('success') }}

</div>

@endif


@if(session('error'))

<div style="
background:#fee2e2;
padding:10px;
margin-bottom:15px;
border-radius:8px;">

{{ session('error') }}

</div>

@endif


<table width="100%" cellpadding="10">

<thead>

<tr style="background:#f1f5f9">

<th>ID</th>
<th>Nombre</th>
<th>Código</th>
<th>Aula</th>
<th>Horario</th>
<th>Alumnos</th>
<th>Cupo</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@forelse($grupos as $grupo)

<tr>

<td>{{ $grupo->id }}</td>

<td>{{ $grupo->nombre }}</td>

<td>{{ $grupo->codigo }}</td>

<td>{{ $grupo->aula }}</td>

<td>{{ $grupo->horario }}</td>

<td>{{ $grupo->postulantes_count }}</td>

<td>

{{ $grupo->postulantes_count }}

/

{{ $grupo->capacidad_maxima }}

</td>

<td>

<a href="{{ route('grupos.show',$grupo) }}">

Ver

</a>

|

<a href="{{ route('grupos.edit',$grupo) }}">

Editar

</a>

|

<form
action="{{ route('grupos.destroy',$grupo) }}"
method="POST"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('¿Eliminar grupo?')">

Eliminar

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="8">

No existen grupos

</td>

</tr>

@endforelse

</tbody>

</table>

<br>

{{ $grupos->links() }}

</div>

@endsection