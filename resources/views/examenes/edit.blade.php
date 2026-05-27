@extends('layouts.app')

@section('title','Editar Examen')

@section('header','Editar Examen')

@section('content')

<div class="card">

<form
action="{{ route('examenes.update',$examen) }}"
method="POST">

@csrf
@method('PUT')

@include('examenes.form')

</form>

</div>

@endsection