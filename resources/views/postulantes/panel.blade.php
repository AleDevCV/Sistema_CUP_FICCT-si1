@extends('layouts.app')

@section('title','Mi Panel Estudiantil')

@section('header','Panel de ' . $postulante->nombre_completo)

@section('content')

<div class="card">

<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:20px;
margin-bottom:30px;">

{{-- BLOQUE 1: Estado de Admisión --}}
<div style="
background:linear-gradient(135deg,
{{ $postulante->estado_final === 'APROBADO' ? '#15803d,#16a34a' : '' }}
{{ $postulante->estado_final === 'REPROBADO' ? '#b91c1c,#dc2626' : '' }}
{{ $postulante->estado_final === 'HABILITADO' ? '#1e40af,#3b82f6' : '' }}
{{ $postulante->estado_final === 'PENDIENTE' ? '#ea580c,#f97316' : '' }}
{{ $postulante->estado_final === 'Aprobado sin Cupo' ? '#b45309,#d97706' : '' }}
{{ !in_array($postulante->estado_final, ['APROBADO','REPROBADO','HABILITADO','PENDIENTE','Aprobado sin Cupo']) ? '#64748b,#94a3b8' : '' }}
);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">
Estado de Admisión
</div>

<div style="font-size:28px;font-weight:700;margin-bottom:8px;">
{{ $postulante->estado_final }}
</div>

@if($postulante->carreraAdmitida)
<div style="font-size:14px;opacity:0.9;">
{{ $postulante->carreraAdmitida->nombre }}
</div>
@endif

</div>


{{-- BLOQUE 2: Logística --}}
<div style="
background:linear-gradient(135deg,#0891b2,#06b6d4);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">
Logística
</div>

@if($postulante->grupo)
<div style="font-size:20px;font-weight:700;margin-bottom:6px;">
{{ $postulante->grupo->nombre }}
</div>

@if($postulante->grupo->aula)
<div style="font-size:16px;opacity:0.9;margin-bottom:4px;">
🏫 {{ $postulante->grupo->aula }}
</div>
@endif

@if($postulante->grupo->horario)
<div style="font-size:14px;opacity:0.85;">
🕐 {{ $postulante->grupo->horario }}
</div>
@endif

@else
<div style="font-size:16px;opacity:0.85;">
Sin grupo asignado aún
</div>
@endif

</div>


{{-- BLOQUE 3: Rendimiento Académico --}}
<div style="
background:linear-gradient(135deg,#7c3aed,#a855f7);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">
Promedio Final
</div>

<div style="font-size:36px;font-weight:700;">
{{ number_format($postulante->promedio_final, 2) }}
</div>

@if($postulante->promedio_final >= 60)
<div style="font-size:14px;opacity:0.9;margin-top:4px;">
✅ Sobre el umbral
</div>
@else
<div style="font-size:14px;opacity:0.9;margin-top:4px;">
❌ Bajo el umbral
</div>
@endif

</div>

</div>


{{-- BLOQUE 4: Detalle de Exámenes --}}
<div style="
border:1px solid #e2e8f0;
border-radius:14px;
overflow:hidden;">

<div style="
padding:18px 24px;
background:linear-gradient(135deg,#1e40af,#3b82f6);
color:white;">

<h3 style="margin:0;font-size:18px;">📋 Detalle de Exámenes</h3>

</div>

@php
$examenesPorMateria = $postulante->examenes->groupBy('materia_id');
@endphp

@if($examenesPorMateria->isNotEmpty())

<table
width="100%"
cellpadding="10"
style="border-collapse:collapse;">

<thead>

<tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">

<th style="text-align:left;">Materia</th>
<th style="text-align:left;">Examen #</th>
<th style="text-align:left;">Nota</th>
<th style="text-align:left;">Peso (%)</th>
<th style="text-align:left;">Ponderación</th>

</tr>

</thead>

<tbody>

@foreach($examenesPorMateria as $materiaId => $examenes)
@foreach($examenes as $examen)

<tr style="border-bottom:1px solid #f1f5f9;">

<td>
<strong>{{ $examen->materia?->nombre ?? 'Materia #'.$materiaId }}</strong>
</td>

<td>
{{ $examen->numero_examen }}
</td>

<td>
<span style="
font-weight:600;
color:{{ $examen->nota >= 51 ? '#16a34a' : '#dc2626' }};">
{{ number_format($examen->nota, 2) }}
</span>
</td>

<td>
{{ number_format($examen->porcentaje, 2) }}%
</td>

<td>
{{ number_format($examen->notaFinal(), 2) }}
</td>

</tr>

@endforeach
@endforeach

</tbody>

</table>

@else
<div style="
text-align:center;
padding:40px;
color:#64748b;">

<p style="font-size:16px;margin:0;">
No hay exámenes registrados aún.
</p>

</div>
@endif

</div>

</div>

@endsection
