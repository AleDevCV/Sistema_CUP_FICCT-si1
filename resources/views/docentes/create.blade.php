@extends('layouts.app')

@section('title','Nuevo Docente')

@section('header','Registrar Docente')

@section('content')

<div class="card">

<form
action="{{ route('docentes.store') }}"
method="POST">

@csrf

@include('docentes.form')

</form>

</div>

@endsection