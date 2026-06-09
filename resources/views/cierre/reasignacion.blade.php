@extends('layouts.app')

@section('title','Reasignación de Cupos')

@section('header','Panel de Reasignación por Mérito y Cupos')

@section('content')

<div class="card" style="border-left:4px solid #0891b2;">

<div style="
display:flex;
align-items:center;
gap:12px;
margin-bottom:24px;
padding:16px;
background:#ecfeff;
border-radius:10px;
border:1px solid #a5f3fc;">

<span style="font-size:28px;">🏆</span>

<div>
<h3 style="margin:0;color:#0e7490;">Asignación Final por Méritos y Cupos</h3>
<p style="margin:4px 0 0;color:#155e75;font-size:14px;">
Los aprobados se ordenan por promedio (descendente). Se asigna cupo en 1ra opción. Si se agota, failover a 2da opción.
</p>
</div>

</div>


@if(session('success'))
<div style="
background:#dcfce7;
color:#166534;
padding:14px;
margin-bottom:20px;
border-radius:10px;
border:1px solid #bbf7d0;">
{{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="
background:#fee2e2;
color:#991b1b;
padding:14px;
margin-bottom:20px;
border-radius:10px;
border:1px solid #fecaca;">
{{ session('error') }}
</div>
@endif


<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;">

<div style="
background:linear-gradient(135deg,#0891b2,#06b6d4);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Total Aprobados
</div>

<div style="font-size:36px;font-weight:700;">
{{ $totalAprobados }}
</div>

</div>


<div style="
background:linear-gradient(135deg,#15803d,#16a34a);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Cupos Totales
</div>

<div style="font-size:36px;font-weight:700;">
{{ $totalCupos }}
</div>

</div>


<div style="
background:linear-gradient(135deg,{{ $excedentesEstimados > 0 ? '#b91c1c,#dc2626' : '#15803d,#16a34a' }});
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Excedentes Estimados
</div>

<div style="font-size:36px;font-weight:700;">
{{ $excedentesEstimados }}
</div>

</div>

</div>


<h3 style="margin-bottom:16px;color:#0f172a;">Estatus de Cupos por Carrera</h3>

<table
width="100%"
cellpadding="10"
style="border-collapse:collapse;margin-bottom:30px;">

<thead>

<tr style="background:#f1f5f9;">

<th style="text-align:left;">Carrera</th>
<th style="text-align:left;">Cupo Total</th>
<th style="text-align:left;">Aprobados (1ra Opción)</th>
<th style="text-align:left;">Estado</th>

</tr>

</thead>

<tbody>

@forelse($carreras as $carrera)

<tr style="border-bottom:1px solid #f1f5f9;">

<td>
<strong>{{ $carrera->nombre }}</strong>
</td>

<td>
{{ $carrera->cupo }}
</td>

<td>
{{ $carrera->aprobados_count ?? 0 }}
</td>

<td>
@php
$dif = (int)$carrera->cupo - (int)($carrera->aprobados_count ?? 0);
@endphp
@if($dif > 0)
<span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;">
{{ $dif }} plazas libres
</span>
@elseif($dif === 0)
<span style="background:#fffbeb;color:#92400e;padding:4px 10px;border-radius:6px;">
Completo
</span>
@else
<span style="background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:6px;">
{{ abs($dif) }} excedentes
</span>
@endif
</td>

</tr>

@empty

<tr>
<td colspan="4">No hay carreras registradas.</td>
</tr>

@endforelse

</tbody>

</table>


@if($totalAprobados > 0)
<form
method="POST"
action="{{ route('reasignacion.ejecutar') }}"
onsubmit="return confirm('¿Estás seguro? Se asignarán los {{ $totalAprobados }} aprobados a sus carreras por orden de mérito. Los excedentes irán a 2da opción o quedarán sin cupo.')"
style="text-align:center;">

@csrf

<button
type="submit"
style="
padding:16px 40px;
background:linear-gradient(135deg,#0891b2,#06b6d4);
color:white;
border:none;
border-radius:12px;
font-size:17px;
font-weight:700;
cursor:pointer;
box-shadow:0 4px 14px rgba(6,182,212,0.4);
transition:0.2s;"
onmouseover="this.style.transform='scale(1.03)'"
onmouseout="this.style.transform='scale(1)'">

🏆 Ejecutar Asignación de Cupos y Failover

</button>

</form>
@else
<div style="
text-align:center;
padding:40px;
background:#f8fafc;
border-radius:12px;
color:#64748b;">

<p style="font-size:18px;">
No hay postulantes aprobados aún. Ejecuta primero el Cierre Académico (CU13).
</p>

</div>
@endif

</div>

@endsection
