@extends('layouts.app')

@section('title','Editar Examen')

@section('header','Editar Examen')

@section('content')

<div class="card">

@php $esDocente = auth()->user()->hasRole('Docente'); @endphp

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
action="{{ route('examenes.update', $examen->id) }}"
method="POST">

@csrf
@method('PUT')

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">

<div>
<label>Postulante</label>
<input
type="text"
value="{{ $examen->postulante?->nombre_completo ?? '—' }}"
style="width:100%;padding:10px;background:#f1f5f9;border:1px solid #d1d5db;border-radius:6px;"
readonly>
</div>

<div>
<label>Materia</label>
<input
type="text"
value="{{ $examen->materia?->nombre ?? '—' }}"
style="width:100%;padding:10px;background:#f1f5f9;border:1px solid #d1d5db;border-radius:6px;"
readonly>
</div>

<div>
<label>Número de examen</label>
<input
type="text"
value="{{ $examen->numero_examen }}"
style="width:100%;padding:10px;background:#f1f5f9;border:1px solid #d1d5db;border-radius:6px;"
readonly>
</div>

<div>
<label>Nota (0-100) *</label>
<input
type="number"
name="nota"
min="0"
max="100"
step="0.01"
value="{{ old('nota', $examen->nota) }}"
style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;"
required>
@error('nota')
<span style="color:#dc2626;font-size:13px;">{{ $message }}</span>
@enderror
</div>

<div>
<label>Porcentaje</label>
<input
type="text"
value="{{ $examen->porcentaje }}%"
style="width:100%;padding:10px;background:#f1f5f9;border:1px solid #d1d5db;border-radius:6px;"
readonly>
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

Guardar Nota

</button>

<a
href="{{ route('examenes.index') }}"
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