@extends('layouts.app')

@section('title','Docentes')

@section('header','Gestión de Docentes')

@section('content')

<div class="card">

<div style="
display:flex;
justify-content:space-between;
margin-bottom:20px;">

<h2>Lista de Docentes</h2>

<a
href="{{ route('docentes.create') }}"
style="
padding:10px 15px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nuevo Docente

</a>

</div>

@if(session('success'))

<div style="
background:#dcfce7;
padding:10px;
margin-bottom:15px;">

{{ session('success') }}

</div>

@endif


<table width="100%" cellpadding="10">

<thead>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Profesión</th>
<th>Contratado</th>
<th>Estado</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@foreach($docentes as $docente)

<tr>

<td>

{{ $docente->id }}

</td>

<td>

{{ $docente->nombre_completo }}

</td>

<td>

{{ $docente->profesion }}

</td>

<td>

{{ $docente->contratado ? 'Sí':'No' }}

</td>

<td>

{{ $docente->estado ? 'Activo':'Inactivo' }}

</td>

<td>

<a href="{{ route('docentes.show',$docente) }}">
Ver
</a>

|

<a href="{{ route('docentes.edit',$docente) }}">
Editar
</a>

|

<form
action="{{ route('docentes.destroy',$docente) }}"
method="POST"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('¿Eliminar docente?')">

Eliminar

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<br>

{{ $docentes->links() }}

</div>

@endsection