@extends('layouts.app')

@section('title','Nueva Materia')

@section('header','Registrar Materia')

@section('content')

<div class="card">

<form
action="{{ route('materias.store') }}"
method="POST">

@csrf

@include('materias.form')

</form>

</div>

@endsection