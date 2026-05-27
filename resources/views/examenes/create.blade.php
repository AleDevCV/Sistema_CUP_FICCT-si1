@extends('layouts.app')

@section('title','Nuevo Examen')

@section('header','Registrar Examen')

@section('content')

<div class="card">

<form
action="{{ route('examenes.store') }}"
method="POST">

@csrf

@include('examenes.form')

</form>

</div>

@endsection