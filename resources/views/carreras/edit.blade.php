@extends('layouts.app')

@section('title','Editar Carrera')

@section('header','Editar carrera')

@section('content')

<div class="card">

<form
action="{{route('carreras.update',$carrera)}}"
method="POST">

@csrf
@method('PUT')

<label>Código</label>

<input
type="text"
name="codigo"
value="{{old('codigo',$carrera->codigo)}}">

<br><br>

<label>Nombre</label>

<input
type="text"
name="nombre"
value="{{old('nombre',$carrera->nombre)}}">

<br><br>

<label>Cupo</label>

<input
type="number"
name="cupo"
value="{{old('cupo',$carrera->cupo)}}">

<br><br>

<label>Gestión</label>

<input
type="number"
name="gestion"
value="{{old('gestion',$carrera->gestion)}}">

<br><br>

<label>Estado</label>

<select name="estado">

<option
value="1"
{{ $carrera->estado ? 'selected':'' }}>

Activo

</option>

<option
value="0"
{{ !$carrera->estado ? 'selected':'' }}>

Inactivo

</option>

</select>

<br><br>

<button>

Actualizar

</button>

</form>

</div>

@endsection