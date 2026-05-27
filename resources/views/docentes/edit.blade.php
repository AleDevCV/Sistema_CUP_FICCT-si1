@extends('layouts.app')

@section('title','Editar Docente')

@section('header','Editar Docente')

@section('content')

<div class="card">

<form
action="{{ route('docentes.update',$docente) }}"
method="POST">

@csrf
@method('PUT')

@include('docentes.form')

</form>

</div>

@endsection