@extends('layouts.app')

@section('title','Postulantes')

@section('header','Gestión de Postulantes')

@section('content')

<div class="card">

<div
style="display:flex;justify-content:space-between;margin-bottom:20px;">

<h2>

Lista de postulantes

</h2>

<a
href="{{ route('postulantes.create') }}"
style="
padding:10px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nuevo

</a>

</div>

<table width="100%" cellpadding="10">

<thead>

<tr>

<th>CI</th>
<th>Nombre</th>
<th>Primera opción</th>
<th>Grupo</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@foreach($postulantes as $postulante)

<tr>

<td>{{ $postulante->ci }}</td>

<td>{{ $postulante->nombre_completo }}</td>

<td>

{{ $postulante->primeraCarrera?->nombre }}

</td>

<td>

{{ $postulante->grupo?->nombre }}

</td>

<td>

<a href="{{ route('postulantes.show',$postulante) }}">
Ver
</a>

|

<a href="{{ route('postulantes.edit',$postulante) }}">
Editar
</a>

|

<form
style="display:inline"
method="POST"
action="{{ route('postulantes.destroy',$postulante) }}">

@csrf
@method('DELETE')

<button>

Eliminar

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<br>

{{ $postulantes->links() }}

</div>

@endsection