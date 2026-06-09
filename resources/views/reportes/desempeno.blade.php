@extends('layouts.app')

@section('title','Estadísticas por Materia')

@section('header','Estadísticas de Desempeño por Materia')

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

<h3 style="margin:0 0 16px;color:#0f172a;">🔍 Filtro de Análisis</h3>

<form method="GET" action="{{ route('reportes.desempeno') }}">

<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:16px;
margin-bottom:16px;">

<div>
<label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px;">Filtrar por Grupo</label>
<select name="grupo_id" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
<option value="">— Todos los grupos —</option>
@foreach($grupos as $grupo)
<option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
{{ $grupo->nombre }}
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
📊 Generar Estadísticas
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

<a href="{{ route('reportes.desempeno') }}" style="
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


{{-- BLOQUE 2: Estadísticas (IMPRIMIBLE) --}}
<div class="print-area" style="
background:white;
border:1px solid #e2e8f0;
border-radius:14px;
padding:24px;">

<div style="text-align:center;margin-bottom:24px;">

<h2 style="margin:0;color:#0f172a;font-size:20px;">
Estadísticas de Rendimiento Académico por Materia — FICCT
</h2>

<p style="margin:6px 0 0;color:#64748b;font-size:13px;">
Generado el {{ now()->format('d/m/Y H:i') }}
@if(request('grupo_id'))
— Grupo: {{ \App\Models\Grupo::find(request('grupo_id'))?->nombre }}
@endif
</p>

<p style="margin:4px 0 0;color:#64748b;font-size:13px;">
Materias analizadas: {{ count($estadisticas) }}
</p>

</div>

@if(count($estadisticas) > 0)

@php
$peorIndice = 0;
@endphp

<table width="100%" cellpadding="10" style="border-collapse:collapse;">

<thead>

<tr style="background:#f1f5f9;border-bottom:2px solid #e2e8f0;">

<th style="text-align:left;">Materia</th>
<th style="text-align:left;">Evaluados</th>
<th style="text-align:left;">Promedio</th>
<th style="text-align:left;">Nota Máx</th>
<th style="text-align:left;">Nota Mín</th>
<th style="text-align:left;">Aprobados</th>
<th style="text-align:left;">Reprobados</th>
<th style="text-align:left;">% Aprobación</th>

</tr>

</thead>

<tbody>

@foreach($estadisticas as $i => $stat)

<tr style="
border-bottom:1px solid #f1f5f9;
{{ $i === $peorIndice && $stat['porcentaje_aprobacion'] < 100 ? 'background:#fef2f2;' : '' }}">

<td>
<strong>{{ $stat['materia']->nombre }}</strong>
@if($i === $peorIndice && count($estadisticas) > 1)
<br><small style="color:#dc2626;">⚠️ Mayor desafío pedagógico</small>
@endif
</td>

<td style="text-align:center;">{{ $stat['total_evaluados'] }}</td>

<td>
<span style="
font-weight:600;
color:{{ $stat['promedio_general'] >= 60 ? '#16a34a' : '#dc2626' }};">
{{ number_format($stat['promedio_general'], 2) }}
</span>
</td>

<td style="color:#16a34a;font-weight:600;">{{ number_format($stat['nota_maxima'], 2) }}</td>

<td style="color:#dc2626;font-weight:600;">{{ number_format($stat['nota_minima'], 2) }}</td>

<td style="text-align:center;">{{ $stat['aprobados'] }}</td>

<td style="text-align:center;">{{ $stat['reprobados'] }}</td>

<td>
<div style="display:flex;align-items:center;gap:8px;">

<div style="
flex:1;
background:#e2e8f0;
border-radius:6px;
height:18px;
overflow:hidden;">

<div style="
width:{{ $stat['porcentaje_aprobacion'] }}%;
height:100%;
background:linear-gradient(90deg,
{{ $stat['porcentaje_aprobacion'] >= 50 ? '#16a34a,#22c55e' : '#dc2626,#ef4444' }});
border-radius:6px;">

</div>

</div>

<span style="
font-weight:700;
font-size:14px;
color:{{ $stat['porcentaje_aprobacion'] >= 50 ? '#16a34a' : '#dc2626' }};">
{{ $stat['porcentaje_aprobacion'] }}%
</span>

</div>
</td>

</tr>

@endforeach

</tbody>

</table>

@else

<div style="text-align:center;padding:60px 20px;color:#64748b;">
<p style="font-size:18px;margin-bottom:8px;">
No hay datos de exámenes disponibles.
</p>
<p style="margin:0;font-size:14px;">
Registra exámenes (CU12) y ejecuta el Cierre Académico (CU13) para generar estadísticas.
</p>
</div>

@endif

</div>

@endsection
