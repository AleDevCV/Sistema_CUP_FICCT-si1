@extends('layouts.app')

@section('title','Nueva Carrera')

@section('header','Crear carrera')

@section('content')

<div class="card">

<form
action="{{ route('carreras.store') }}"
method="POST">

@csrf

<label>Código</label>

<input
type="text"
name="codigo"
value="{{old('codigo')}}">

<br><br>

<label>Nombre</label>

<input
type="text"
name="nombre"
value="{{old('nombre')}}">

<br><br>

<label>Cupo</label>

<input
type="number"
name="cupo"
value="{{old('cupo')}}">

<br><br>

<label>Gestión</label>

<input
type="number"
name="gestion"
value="{{old('gestion')}}">

<br><br>

<label>Estado</label>

<select name="estado">

<option value="1">
Activo
</option>

<option value="0">
Inactivo
</option>

</select>

<br><br>

<label>Materias</label>
<br>
@foreach($materias as $materia)
<label>
    <input type="checkbox" name="materias[]" value="{{ $materia->id }}">
    {{ $materia->nombre }}
</label><br>
@endforeach

<br>

<button>

Guardar

</button>

</form>

</div>

@endsection