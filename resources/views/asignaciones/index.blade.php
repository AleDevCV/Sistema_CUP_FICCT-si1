@extends('layouts.app')

@section('title','Asignaciones Docentes')

@section('header','Asignaciones Docente-Grupo-Materia')

@section('content')

<div class="card">

<div style="display:flex;justify-content:space-between;margin-bottom:20px;">

<h2>Lista de Asignaciones</h2>

<a
href="{{ route('asignaciones.create') }}"
style="
padding:10px 15px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nueva Asignación

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

<table width="100%" cellpadding="10">

<thead>

<tr>

<th>Docente</th>
<th>Grupo</th>
<th>Materia</th>
<th>Carga Actual</th>
<th>Acción</th>

</tr>

</thead>

<tbody>

@forelse($asignaciones as $asig)

<tr>

<td>{{ $asig->docente->nombre_completo }}</td>

<td>{{ $asig->grupo->nombre }}</td>

<td>{{ $asig->materia->nombre }}</td>

<td>

<span style="
padding:4px 10px;
background:{{ $asig->docente->cargaActual() >= 4 ? '#fee2e2' : '#dcfce7' }};
color:{{ $asig->docente->cargaActual() >= 4 ? '#991b1b' : '#166534' }};
border-radius:12px;
font-size:13px;">

{{ $asig->docente->cargaActual() }}/4

</span>

</td>

<td>

<form
style="display:inline"
method="POST"
action="{{ route('asignaciones.destroy', $asig) }}"
onsubmit="return confirm('¿Eliminar esta asignación?')">

@csrf
@method('DELETE')

<button
style="
padding:6px 12px;
background:#dc2626;
color:white;
border:none;
border-radius:6px;
cursor:pointer;">

Eliminar

</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="5" style="text-align:center;">No hay asignaciones registradas.</td>
</tr>

@endforelse

</tbody>

</table>

<br>

{{ $asignaciones->links() }}

</div>

@endsection
