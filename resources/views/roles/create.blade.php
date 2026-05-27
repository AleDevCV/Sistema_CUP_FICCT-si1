@extends('layouts.app')

@section('title','Nuevo Rol')

@section('header','Crear Rol')

@section('content')

<div class="card">

<form
action="{{ route('roles.store') }}"
method="POST">

@csrf

<label>

Nombre

</label>

<br>

<input
type="text"
name="name"
value="{{ old('name') }}"
style="width:100%;padding:10px;">

<br><br>

<label>

Descripción

</label>

<br>

<textarea
name="description"
style="width:100%;padding:10px;">

{{ old('description') }}

</textarea>

<br><br>

<button>

Guardar

</button>

</form>

</div>

@endsection