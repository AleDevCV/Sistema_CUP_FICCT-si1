@extends('layouts.app')

@section('title','Postulantes')

@section('header','Gestión de Postulantes')

@section('content')

<div class="card">

<div
style="display:flex;justify-content:space-between;margin-bottom:20px;">

<h2>

Lista de postulantes

</h2>

<div style="display:flex;gap:10px;">

<a
href="{{ route('registro.create') }}"
style="
padding:10px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Nuevo

</a>

<button
onclick="document.getElementById('modalImportar').style.display='block'"
style="
padding:10px;
background:#059669;
color:white;
border:none;
border-radius:8px;
cursor:pointer;">

Importar CSV

</button>

<a
href="{{ route('postulantes.historial') }}"
style="
padding:10px;
background:#6366f1;
color:white;
text-decoration:none;
border-radius:8px;">

Historial

</a>

</div>

</div>

<!-- Modal Importar CSV -->
<div
id="modalImportar"
style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.6);z-index:1000;align-items:center;justify-content:center;"
onclick="if(event.target===this)this.style.display='none'">

<div
style="background:white;border-radius:12px;padding:30px;width:450px;max-width:90%;">

<h3 style="margin-bottom:15px;">Importar Postulantes CSV</h3>

<form
action="{{ route('postulantes.importar') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<input
type="file"
name="archivo"
accept=".csv,.txt"
required
style="margin-bottom:15px;width:100%;">

<br>

<button
style="
padding:10px 20px;
background:#059669;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
margin-right:10px;">

Subir y procesar

</button>

<button
type="button"
onclick="document.getElementById('modalImportar').style.display='none'"
style="
padding:10px 20px;
background:#6b7280;
color:white;
border:none;
border-radius:8px;
cursor:pointer;">

Cancelar

</button>

</form>

</div>

</div>

<table width="100%" cellpadding="10">

<thead>

<tr>

<th>CI</th>
<th>Nombre</th>
<th>Primera opción</th>
<th>Grupo</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

@foreach($postulantes as $postulante)

<tr>

<td>{{ $postulante->ci }}</td>

<td>{{ $postulante->nombre_completo }}</td>

<td>

{{ $postulante->primeraCarrera?->nombre }}

</td>

<td>

{{ $postulante->grupo?->nombre }}

</td>

<td>

<a href="{{ route('postulantes.show',$postulante) }}">
Ver
</a>

|

<a href="{{ route('postulantes.edit',$postulante) }}">
Editar
</a>

|

<form
style="display:inline"
method="POST"
action="{{ route('postulantes.destroy',$postulante) }}">

@csrf
@method('DELETE')

<button>

Eliminar

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<br>

{{ $postulantes->links() }}

</div>

@endsection