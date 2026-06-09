@extends('layouts.app')

@section('title','Cupos por Carrera')

@section('header','Configuración de Cupos de Admisión')

@section('content')

<div class="card">

@if(session('success'))
<div style="
background:#dcfce7;
color:#166534;
padding:12px;
margin-bottom:15px;
border-radius:8px;">
{{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="
background:#fee2e2;
color:#991b1b;
padding:12px;
margin-bottom:15px;
border-radius:8px;">
@foreach($errors->all() as $error)
<p>{{ $error }}</p>
@endforeach
</div>
@endif

<table width="100%" cellpadding="10">

<thead>

<tr>

<th>Carrera</th>
<th>Inscritos Habilitados</th>
<th>Cupo Actual</th>
<th>Nuevo Cupo</th>
<th>Acción</th>

</tr>

</thead>

<tbody>

@foreach($carreras as $carrera)

@php $inscritos = $carrera->inscritosHabilitadosCount(); @endphp

<tr>

<td><strong>{{ $carrera->nombre }}</strong></td>

<td>

<span style="
padding:4px 10px;
background:#dbeafe;
color:#1e40af;
border-radius:12px;
font-size:14px;">

{{ $inscritos }}

</span>

</td>

<td>

<span style="
padding:4px 10px;
background:#e2e8f0;
color:#475569;
border-radius:12px;
font-size:14px;">

{{ $carrera->cupo }}

</span>

</td>

<td>

<form
method="POST"
action="{{ route('cupos.update', $carrera) }}"
style="display:flex;gap:8px;align-items:center;">

@csrf
@method('PATCH')

<input
type="number"
name="cupo"
min="0"
value="{{ $carrera->cupo }}"
style="width:90px;padding:8px;border:1px solid #d1d5db;border-radius:6px;"
required>

</td>

<td>

<button
style="
padding:8px 16px;
background:#2563eb;
color:white;
border:none;
border-radius:6px;
cursor:pointer;">

Guardar

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection
