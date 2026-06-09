@extends('layouts.app')

@section('title','Cierre Académico')

@section('header','Panel de Cierre Académico')

@section('content')

<div class="card" style="border-left:4px solid #dc2626;">

<div style="
display:flex;
align-items:center;
gap:12px;
margin-bottom:24px;
padding:16px;
background:#fef2f2;
border-radius:10px;
border:1px solid #fecaca;">

<span style="font-size:28px;">⚠️</span>

<div>
<h3 style="margin:0;color:#991b1b;">Zona de Operaciones Críticas</h3>
<p style="margin:4px 0 0;color:#7f1d1d;font-size:14px;">
Una vez ejecutado el cierre, los promedios y estados quedarán registrados de forma permanente.
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
background:linear-gradient(135deg,#1e40af,#3b82f6);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Total Habilitados
</div>

<div style="font-size:36px;font-weight:700;">
{{ $totalHabilitados }}
</div>

</div>


<div style="
background:linear-gradient(135deg,#15803d,#16a34a);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Proyección Aprobados
</div>

<div style="font-size:36px;font-weight:700;">
{{ $proyeccionAprobados }}
</div>

@if($totalHabilitados > 0)
<div style="font-size:13px;margin-top:6px;opacity:0.8;">
{{ round(($proyeccionAprobados/$totalHabilitados)*100, 1) }}%
</div>
@endif

</div>


<div style="
background:linear-gradient(135deg,#b91c1c,#dc2626);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Proyección Reprobados
</div>

<div style="font-size:36px;font-weight:700;">
{{ $proyeccionReprobados }}
</div>

@if($totalHabilitados > 0)
<div style="font-size:13px;margin-top:6px;opacity:0.8;">
{{ round(($proyeccionReprobados/$totalHabilitados)*100, 1) }}%
</div>
@endif

</div>


<div style="
background:linear-gradient(135deg,#7c3aed,#a855f7);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Materias Activas
</div>

<div style="font-size:36px;font-weight:700;">
{{ $totalMaterias }}
</div>

</div>

</div>


<div style="
background:#fffbeb;
border:1px solid #fcd34d;
border-radius:10px;
padding:16px;
margin-bottom:20px;">

<h4 style="margin:0 0 8px;color:#92400e;">📐 Fórmula de Cálculo</h4>

<p style="margin:0;color:#78350f;font-size:14px;line-height:1.6;">
<strong>Promedio_Materia</strong> = &Sigma;notas / cantidad de exámenes<br>
<strong>Aporte_Materia</strong> = Promedio_Materia × (ponderación / 100)<br>
<strong>Promedio Final</strong> = &Sigma; todos los Aporte_Materia<br>
<strong>Umbral:</strong> ≥ 60 = APROBADO | &lt; 60 = REPROBADO
</p>

</div>


<form
method="POST"
action="{{ route('cierre.ejecutar') }}"
onsubmit="return confirm('¿Estás seguro? Esta acción es irreversible. Se calcularán los promedios y estados de todos los postulantes habilitados.')"
style="text-align:center;">

@csrf

<button
type="submit"
style="
padding:16px 40px;
background:linear-gradient(135deg,#b91c1c,#dc2626);
color:white;
border:none;
border-radius:12px;
font-size:17px;
font-weight:700;
cursor:pointer;
box-shadow:0 4px 14px rgba(220,38,38,0.4);
transition:0.2s;"
onmouseover="this.style.transform='scale(1.03)'"
onmouseout="this.style.transform='scale(1)'">

🔒 Ejecutar Cierre Académico Definitivo

</button>

</form>

</div>

@endsection
