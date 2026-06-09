@extends('layouts.app')

@section('title','Bitácora de Auditoría')

@section('header','Auditoría de Operaciones')

@section('content')

<div class="card">

<h2>Registro de Auditoría</h2>

<br>

<form method="GET" action="{{ route('auditorias.index') }}" style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">

<input
type="text"
name="user_id"
placeholder="ID Usuario"
value="{{ request('user_id') }}"
style="padding:8px;border:1px solid #d1d5db;border-radius:6px;width:120px;">

<select name="modelo" style="padding:8px;border:1px solid #d1d5db;border-radius:6px;">
<option value="">Todos los modelos</option>
<option value="User" {{ request('modelo') == 'User' ? 'selected' : '' }}>User</option>
<option value="Carrera" {{ request('modelo') == 'Carrera' ? 'selected' : '' }}>Carrera</option>
<option value="Postulante" {{ request('modelo') == 'Postulante' ? 'selected' : '' }}>Postulante</option>
<option value="Docente" {{ request('modelo') == 'Docente' ? 'selected' : '' }}>Docente</option>
</select>

<select name="accion" style="padding:8px;border:1px solid #d1d5db;border-radius:6px;">
<option value="">Todas las acciones</option>
<option value="created" {{ request('accion') == 'created' ? 'selected' : '' }}>Creado</option>
<option value="updated" {{ request('accion') == 'updated' ? 'selected' : '' }}>Actualizado</option>
<option value="deleted" {{ request('accion') == 'deleted' ? 'selected' : '' }}>Eliminado</option>
</select>

<button style="
padding:8px 16px;
background:#2563eb;
color:white;
border:none;
border-radius:6px;
cursor:pointer;">
Filtrar
</button>

<a href="{{ route('auditorias.index') }}" style="
padding:8px 16px;
background:#6b7280;
color:white;
text-decoration:none;
border-radius:6px;">
Limpiar
</a>

</form>

<table width="100%" cellpadding="8">

<thead>

<tr>

<th>Fecha/Hora</th>
<th>IP</th>
<th>Usuario</th>
<th>Acción</th>
<th>Modelo</th>
<th>ID</th>
<th>Valores Anteriores → Nuevos</th>

</tr>

</thead>

<tbody>

@forelse($auditorias as $audit)

<tr>

<td style="white-space:nowrap;">{{ $audit->created_at->format('d/m/Y H:i') }}</td>

<td>{{ $audit->ip_address ?? '—' }}</td>

<td>{{ $audit->user?->name ?? 'Sistema' }}</td>

<td>

@if($audit->accion === 'created')
<span style="
padding:3px 8px;
background:#dcfce7;
color:#166534;
border-radius:8px;
font-size:12px;">
Creado
</span>

@elseif($audit->accion === 'updated')
<span style="
padding:3px 8px;
background:#fef3c7;
color:#92400e;
border-radius:8px;
font-size:12px;">
Actualizado
</span>

@elseif($audit->accion === 'deleted')
<span style="
padding:3px 8px;
background:#fee2e2;
color:#991b1b;
border-radius:8px;
font-size:12px;">
Eliminado
</span>

@endif

</td>

<td>{{ $audit->modelo }}</td>

<td>{{ $audit->modelo_id }}</td>

<td style="max-width:300px;font-size:12px;">

@if($audit->accion === 'created')
<span style="color:#10b981;">
Registro creado con {{ count($audit->valores_nuevos ?? []) }} campos.
</span>

@elseif($audit->accion === 'deleted')
<span style="color:#ef4444;">
Registro eliminado ({{ $audit->modelo }} #{{ $audit->modelo_id }}).
</span>

@elseif($audit->accion === 'updated')
@php
$skip = ['updated_at', 'created_at', 'password', 'remember_token', 'email_verified_at'];
$cambios = [];
foreach ($audit->valores_nuevos ?? [] as $campo => $nuevo) {
    if (in_array($campo, $skip)) continue;
    $anterior = $audit->valores_anteriores[$campo] ?? '—';

    if (is_bool($nuevo) || $nuevo === '1' || $nuevo === '0' || $anterior === true || $anterior === false) {
        $nuevo = $nuevo == '1' || $nuevo === true ? 'Sí' : ($nuevo == '0' || $nuevo === false ? 'No' : $nuevo);
        $anterior = $anterior == '1' || $anterior === true ? 'Sí' : ($anterior == '0' || $anterior === false ? 'No' : $anterior);
    }
    $cambios[] = "<strong>{$campo}</strong>: {$anterior} → {$nuevo}";
}
@endphp

@foreach($cambios as $cambio)
<div style="
background:#f8fafc;
border-left:3px solid #3b82f6;
padding:4px 8px;
margin-bottom:3px;
border-radius:4px;">
{!! $cambio !!}
</div>
@endforeach

@if(empty($cambios))
<span style="color:#6b7280;">Sin cambios visibles.</span>
@endif

@endif

</td>

</tr>

@empty

<tr>
<td colspan="7" style="text-align:center;">No hay registros de auditoría.</td>
</tr>

@endforelse

</tbody>

</table>

<br>

{{ $auditorias->links() }}

</div>

@endsection
