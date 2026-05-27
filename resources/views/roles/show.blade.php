@extends('layouts.app')

@section('title','Detalle Rol')

@section('header','Detalle del Rol')

@section('content')

<div class="card">

<h2>

{{ $role->name }}

</h2>

<br>

<p>

<strong>Descripción:</strong>

{{ $role->description ?? 'Sin descripción' }}

</p>

<br>

<p>

<strong>Usuarios asignados:</strong>

{{ $role->users->count() }}

</p>

<br>

@if($role->users->count())

<h3>

Usuarios asociados

</h3>

<br>

<ul>

@foreach($role->users as $user)

<li>

{{ $user->name }}

({{ $user->email }})

</li>

@endforeach

</ul>

@endif

<br>

<a
href="{{ route('roles.index') }}">

Volver

</a>

</div>

@endsection