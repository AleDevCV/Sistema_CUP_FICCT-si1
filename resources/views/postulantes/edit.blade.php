@extends('layouts.app')

@section('title','Editar Postulante')

@section('header','Editar Postulante')

@section('content')

<div class="card">

<form
action="{{ route('postulantes.update',$postulante) }}"
method="POST">

@csrf
@method('PUT')

@include('postulantes.form')

</form>

</div>

@endsection