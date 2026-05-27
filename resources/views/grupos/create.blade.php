@extends('layouts.app')

@section('title','Nuevo Grupo')

@section('header','Registrar Grupo')

@section('content')

<div class="card">

<form
action="{{ route('grupos.store') }}"
method="POST">

@csrf

@include('grupos.form')

</form>

</div>

@endsection