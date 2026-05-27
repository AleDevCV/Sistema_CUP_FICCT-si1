@extends('layouts.app')

@section('title','Editar Grupo')

@section('header','Editar Grupo')

@section('content')

<div class="card">

<form
action="{{ route('grupos.update',$grupo) }}"
method="POST">

@csrf
@method('PUT')

@include('grupos.form')

</form>

</div>

@endsection