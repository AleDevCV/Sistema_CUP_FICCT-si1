@extends('layouts.app')

@section('title','Editar Materia')

@section('header','Editar Materia')

@section('content')

<div class="card">

<form
action="{{ route('materias.update',$materia) }}"
method="POST">

@csrf
@method('PUT')

@include('materias.form')

</form>

</div>

@endsection