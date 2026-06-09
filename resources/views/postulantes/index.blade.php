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

<!-- Filtros de estado -->
<div style="display:flex;gap:10px;margin-bottom:20px;">

<a
href="{{ route('postulantes.index') }}"
style="
padding:8px 16px;
background:#6b7280;
color:white;
text-decoration:none;
border-radius:6px;
{{ !request('estado') ? 'font-weight:bold;' : '' }}">

Todos

</a>

<a
href="{{ route('postulantes.index', ['estado' => 'PENDIENTE']) }}"
style="
padding:8px 16px;
background:#f59e0b;
color:white;
text-decoration:none;
border-radius:6px;
{{ request('estado') == 'PENDIENTE' ? 'font-weight:bold;' : '' }}">

Pendientes

</a>

<a
href="{{ route('postulantes.index', ['estado' => 'HABILITADO']) }}"
style="
padding:8px 16px;
background:#3b82f6;
color:white;
text-decoration:none;
border-radius:6px;
{{ request('estado') == 'HABILITADO' ? 'font-weight:bold;' : '' }}">

Habilitados

</a>

<a
href="{{ route('postulantes.index', ['estado' => 'APROBADO']) }}"
style="
padding:8px 16px;
background:#10b981;
color:white;
text-decoration:none;
border-radius:6px;
{{ request('estado') == 'APROBADO' ? 'font-weight:bold;' : '' }}">

Aprobados

</a>

<a
href="{{ route('postulantes.index', ['estado' => 'REPROBADO']) }}"
style="
padding:8px 16px;
background:#dc2626;
color:white;
text-decoration:none;
border-radius:6px;
{{ request('estado') == 'REPROBADO' ? 'font-weight:bold;' : '' }}">

Reprobados

</a>

</div>

<table width="100%" cellpadding="10">

<thead>

<tr>

<th>CI</th>
<th>Nombre</th>
<th>Primera opción</th>
<th>Estado</th>
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

@if($postulante->estado_final === 'PENDIENTE')
<span style="
padding:4px 10px;
background:#fef3c7;
color:#92400e;
border-radius:12px;
font-size:13px;">
Pendiente
</span>
@elseif($postulante->estado_final === 'HABILITADO')
<span style="
padding:4px 10px;
background:#dbeafe;
color:#1e40af;
border-radius:12px;
font-size:13px;">
Habilitado
</span>
@elseif($postulante->estado_final === 'APROBADO')
<span style="
padding:4px 10px;
background:#dcfce7;
color:#166534;
border-radius:12px;
font-size:13px;">
Aprobado
</span>

@elseif($postulante->estado_final === 'REPROBADO')
<span style="
padding:4px 10px;
background:#fee2e2;
color:#991b1b;
border-radius:12px;
font-size:13px;">
Reprobado
</span>

@else
<span style="
padding:4px 10px;
background:#e2e8f0;
color:#475569;
border-radius:12px;
font-size:13px;">
—
</span>

@endif

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