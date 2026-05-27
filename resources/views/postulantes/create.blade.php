@extends('layouts.app')

@section('title','Nuevo Postulante')

@section('header','Registrar Postulante')

@section('content')

<div class="card">

<form
action="{{ route('postulantes.store') }}"
method="POST">

@csrf

@include('postulantes.form')

</form>

</div>

@endsection