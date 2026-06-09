@extends('layouts.app')

@section('title','Mi Perfil')

@section('header','Configuración de Perfil')

@section('content')

<div class="card">

@if(session('success'))
<div style="
background:#dcfce7;
color:#166534;
padding:12px;
margin-bottom:20px;
border-radius:8px;">
{{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('perfil.update') }}">
@csrf
@method('PATCH')

<!-- Sección: Información Personal -->
<h3 style="margin-bottom:15px;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
Información Personal
</h3>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:30px;">

<div>
<label>Nombre *</label>
<input
type="text"
name="name"
value="{{ old('name', $user->name) }}"
style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;"
required>
@error('name')
<span style="color:#dc2626;font-size:13px;">{{ $message }}</span>
@enderror
</div>

<div>
<label>Email *</label>
<input
type="email"
name="email"
value="{{ old('email', $user->email) }}"
style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;"
required>
@error('email')
<span style="color:#dc2626;font-size:13px;">{{ $message }}</span>
@enderror
</div>

</div>

<!-- Sección: Cambiar Contraseña -->
<h3 style="margin-bottom:15px;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
Cambiar Contraseña (opcional)
</h3>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:30px;">

<div>
<label>Contraseña actual</label>
<input
type="password"
name="current_password"
style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;">
@error('current_password')
<span style="color:#dc2626;font-size:13px;">{{ $message }}</span>
@enderror
</div>

<div></div>

<div>
<label>Nueva contraseña</label>
<input
type="password"
name="password"
style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;">
@error('password')
<span style="color:#dc2626;font-size:13px;">{{ $message }}</span>
@enderror
</div>

<div>
<label>Confirmar nueva contraseña</label>
<input
type="password"
name="password_confirmation"
style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;">
</div>

</div>

<button
style="
background:#2563eb;
color:white;
padding:12px 25px;
border:none;
border-radius:8px;
cursor:pointer;
font-size:15px;">

Guardar cambios

</button>

</form>

</div>

@endsection
