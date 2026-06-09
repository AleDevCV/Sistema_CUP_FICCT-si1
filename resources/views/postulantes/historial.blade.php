@extends('layouts.app')

@section('title','Historial de Importaciones')

@section('header','Historial de Importaciones CSV')

@section('content')

<div class="card">

<a
href="{{ route('postulantes.index') }}"
style="
padding:8px 15px;
background:#6b7280;
color:white;
text-decoration:none;
border-radius:6px;
margin-bottom:15px;
display:inline-block;">

← Volver a Postulantes

</a>

@if(session('success'))
<div style="
background:#dcfce7;
padding:10px;
margin:15px 0;
border-radius:6px;">
{{ session('success') }}
</div>
@endif

<table width="100%" cellpadding="10">

<thead>

<tr>

<th>#</th>
<th>Nombre CSV</th>
<th>Fecha/Hora</th>
<th>Registros creados</th>
<th>Acción</th>

</tr>

</thead>

<tbody>

@forelse($importaciones as $index => $imp)

<tr>

<td>{{ $index + 1 }}</td>

<td>{{ $imp->nombre_archivo }}</td>

<td>{{ $imp->created_at->format('d/m/Y H:i') }}</td>

<td>{{ $imp->postulantes_count }}</td>

<td>

@if(!$imp->revertida)

<form
style="display:inline"
method="POST"
action="{{ route('postulantes.revertir', $imp) }}"
onsubmit="return confirm('¿Eliminar TODOS los postulantes de esta importación? Esta acción no se puede deshacer.')">

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

Revertir

</button>

</form>

@else

<span
style="
padding:6px 12px;
background:#6b7280;
color:white;
border-radius:6px;
font-size:14px;">

Revertida

</span>

@endif

</td>

</tr>

@empty

<tr>

<td colspan="5" style="text-align:center;">

No hay importaciones registradas.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection
