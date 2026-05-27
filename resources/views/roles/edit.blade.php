@extends('layouts.app')

@section('title','Editar Rol')

@section('header','Editar Rol')

@section('content')

<div class="card">

<form
action="{{ route('roles.update',$role) }}"
method="POST">

@csrf
@method('PUT')

<label>

Nombre

</label>

<br>

<input
type="text"
name="name"
value="{{ old('name',$role->name) }}"
style="width:100%;padding:10px;">

<br><br>

<label>

Descripción

</label>

<br>

<textarea
name="description"
style="width:100%;padding:10px;">

{{ old('description',$role->description) }}

</textarea>

<br><br>

<button>

Actualizar

</button>

</form>

</div>

@endsection