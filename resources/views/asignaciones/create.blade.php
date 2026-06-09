@extends('layouts.app')

@section('title','Nueva Asignación')

@section('header','Crear Asignación Docente-Grupo-Materia')

@section('content')

<div class="card">

@if($errors->any())
<div style="
background:#fee2e2;
color:#991b1b;
padding:15px;
margin-bottom:20px;
border-radius:10px;">

<ul>

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>
@endif

<form
action="{{ route('asignaciones.store') }}"
method="POST">

@csrf

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">

<div>

<label>Docente *</label>

<select
name="docente_id"
style="width:100%;padding:10px;"
required>

<option value="">Seleccione un docente</option>

@foreach($docentes as $docente)

<option
value="{{ $docente->id }}"
{{ old('docente_id') == $docente->id ? 'selected' : '' }}>

{{ $docente->nombre_completo }}
({{ $docente->cargaActual() }}/4 grupos)

</option>

@endforeach

</select>

</div>

<div>

<label>Grupo *</label>

<select
name="grupo_id"
style="width:100%;padding:10px;"
required>

<option value="">Seleccione un grupo</option>

@foreach($grupos as $grupo)

<option
value="{{ $grupo->id }}"
{{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>

{{ $grupo->nombre }}

</option>

@endforeach

</select>

</div>

<div>

<label>Materia *</label>

<select
name="materia_id"
style="width:100%;padding:10px;"
required>

<option value="">Seleccione una materia</option>

@foreach($materias as $materia)

<option
value="{{ $materia->id }}"
{{ old('materia_id') == $materia->id ? 'selected' : '' }}>

{{ $materia->nombre }}

</option>

@endforeach

</select>

</div>

</div>

<br>

<button
style="
background:#2563eb;
color:white;
padding:12px 20px;
border:none;
border-radius:8px;
cursor:pointer;">

Guardar Asignación

</button>

<a
href="{{ route('asignaciones.index') }}"
style="
margin-left:10px;
padding:12px 20px;
background:#6b7280;
color:white;
text-decoration:none;
border-radius:8px;">

Cancelar

</a>

</form>

</div>

@endsection
