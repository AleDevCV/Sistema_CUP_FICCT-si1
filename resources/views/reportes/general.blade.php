@extends('layouts.app')

@section('title','Reportes Generales')

@section('header','Reportes Generales de Postulantes')

@section('content')

<style>
@media print {
    .sidebar, .header, .menu-btn, .no-print, .card {
        display: none !important;
    }

    .main {
        margin-left: 0 !important;
    }

    .print-area {
        display: block !important;
        background: white !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
    }

    .print-area table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .print-area th {
        background: #f1f5f9 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    body {
        background: white !important;
    }

    @page {
        margin: 1.5cm;
        size: letter landscape;
    }
}

.print-area {
    display: block;
}
</style>


{{-- BLOQUE 1: Filtros (NO IMPRIMIBLE) --}}
<div class="no-print" style="
background:white;
border:1px solid #e2e8f0;
border-radius:14px;
padding:24px;
margin-bottom:24px;">

<h3 style="margin:0 0 16px;color:#0f172a;">🔍 Filtros de Reporte</h3>

<form method="GET" action="{{ route('reportes.general') }}">

<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:16px;
margin-bottom:16px;">

<div>
<label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px;">Estado</label>
<select name="estado" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
<option value="">— Todos —</option>
<option value="PENDIENTE" {{ request('estado') === 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
<option value="HABILITADO" {{ request('estado') === 'HABILITADO' ? 'selected' : '' }}>Habilitado</option>
<option value="APROBADO" {{ request('estado') === 'APROBADO' ? 'selected' : '' }}>Aprobado</option>
<option value="REPROBADO" {{ request('estado') === 'REPROBADO' ? 'selected' : '' }}>Reprobado</option>
<option value="Aprobado sin Cupo" {{ request('estado') === 'Aprobado sin Cupo' ? 'selected' : '' }}>Aprobado sin Cupo</option>
</select>
</div>

<div>
<label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px;">Grupo</label>
<select name="grupo_id" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
<option value="">— Todos —</option>
@foreach($grupos as $grupo)
<option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
{{ $grupo->nombre }}
</option>
@endforeach
</select>
</div>

<div>
<label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px;">Carrera Asignada</label>
<select name="carrera_id" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
<option value="">— Todas —</option>
@foreach($carreras as $carrera)
<option value="{{ $carrera->id }}" {{ request('carrera_id') == $carrera->id ? 'selected' : '' }}>
{{ $carrera->nombre }}
</option>
@endforeach
</select>
</div>

</div>

<div style="display:flex;gap:10px;">

<button type="submit" style="
padding:10px 24px;
background:#2563eb;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
font-size:14px;">
📋 Generar Reporte
</button>

<button type="button" onclick="window.print()" style="
padding:10px 24px;
background:#059669;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
font-size:14px;">
🖨️ Imprimir / Guardar PDF
</button>

<a href="{{ route('reportes.general') }}" style="
padding:10px 24px;
background:#6b7280;
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
🔄 Limpiar Filtros
</a>

</div>

</form>

</div>


{{-- BLOQUE 2: Reporte (IMPRIMIBLE) --}}
<div class="print-area" style="
background:white;
border:1px solid #e2e8f0;
border-radius:14px;
padding:24px;">

<div style="text-align:center;margin-bottom:24px;">

<h2 style="margin:0;color:#0f172a;font-size:20px;">
Reporte Oficial de Admisión Universitaria — FICCT
</h2>

<p style="margin:6px 0 0;color:#64748b;font-size:13px;">
Generado el {{ now()->format('d/m/Y H:i') }}
@if(request('estado'))
— Filtro: {{ request('estado') }}
@endif
@if(request('grupo_id'))
— Grupo: {{ \App\Models\Grupo::find(request('grupo_id'))?->nombre }}
@endif
@if(request('carrera_id'))
— Carrera: {{ \App\Models\Carrera::find(request('carrera_id'))?->nombre }}
@endif
</p>

<p style="margin:4px 0 0;color:#64748b;font-size:13px;">
Total de registros: {{ $postulantes->count() }}
</p>

</div>

@if($postulantes->isNotEmpty())

<table width="100%" cellpadding="10" style="border-collapse:collapse;">

<thead>

<tr style="background:#f1f5f9;border-bottom:2px solid #e2e8f0;">

<th style="text-align:left;">CI</th>
<th style="text-align:left;">Nombre Completo</th>
<th style="text-align:left;">Promedio</th>
<th style="text-align:left;">Estado</th>
<th style="text-align:left;">Carrera Asignada</th>
<th style="text-align:left;">Grupo</th>

</tr>

</thead>

<tbody>

@foreach($postulantes as $postulante)

<tr style="border-bottom:1px solid #f1f5f9;">

<td>{{ $postulante->ci }}</td>

<td>{{ $postulante->nombre_completo }}</td>

<td>
<span style="
font-weight:600;
color:{{ $postulante->promedio_final >= 60 ? '#16a34a' : '#dc2626' }};">
{{ number_format($postulante->promedio_final, 2) }}
</span>
</td>

<td>
@php
$badgeColor = match($postulante->estado_final) {
'APROBADO' => 'background:#dcfce7;color:#166534;',
'REPROBADO' => 'background:#fee2e2;color:#991b1b;',
'HABILITADO' => 'background:#dbeafe;color:#1e40af;',
'PENDIENTE' => 'background:#fef3c7;color:#92400e;',
'Aprobado sin Cupo' => 'background:#fef3c7;color:#92400e;',
default => 'background:#e2e8f0;color:#475569;'
};
@endphp
<span style="padding:4px 10px;border-radius:12px;font-size:13px;{{ $badgeColor }}">
{{ $postulante->estado_final }}
</span>
</td>

<td>{{ $postulante->carreraAdmitida?->nombre ?? '—' }}</td>

<td>{{ $postulante->grupo?->nombre ?? '—' }}</td>

</tr>

@endforeach

</tbody>

</table>

@else

<div style="text-align:center;padding:60px 20px;color:#64748b;">

<p style="font-size:18px;margin-bottom:8px;">
No se encontraron postulantes con los filtros seleccionados.
</p>

</div>

@endif

</div>

@endsection
