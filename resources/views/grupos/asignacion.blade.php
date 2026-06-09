@extends('layouts.app')

@section('title','Asignación Automática de Grupos')

@section('header','Panel de Asignación Automática de Grupos')

@section('content')

<div class="card" style="border-left:4px solid #7c3aed;">

<div style="
display:flex;
align-items:center;
gap:12px;
margin-bottom:24px;
padding:16px;
background:#f5f3ff;
border-radius:10px;
border:1px solid #ddd6fe;">

<span style="font-size:28px;">⚙️</span>

<div>
<h3 style="margin:0;color:#6d28d9;">Motor de Asignación Grupal (Límite 70)</h3>
<p style="margin:4px 0 0;color:#4c1d95;font-size:14px;">
Los postulantes habilitados sin grupo se distribuirán automáticamente en aulas de máximo 70 estudiantes.
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
background:linear-gradient(135deg,#6d28d9,#7c3aed);
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
background:linear-gradient(135deg,#ea580c,#f97316);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Sin Grupo Asignado
</div>

<div style="font-size:36px;font-weight:700;">
{{ $sinGrupo }}
</div>

</div>


<div style="
background:linear-gradient(135deg,#0891b2,#06b6d4);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Grupos a Crear
</div>

<div style="font-size:36px;font-weight:700;">
{{ $gruposNecesarios }}
</div>

@if($sinGrupo > 0)
<div style="font-size:13px;margin-top:6px;opacity:0.8;">
CEIL({{ $sinGrupo }} / 70)
</div>
@endif

</div>


<div style="
background:linear-gradient(135deg,#15803d,#16a34a);
color:white;
padding:24px;
border-radius:14px;
text-align:center;">

<div style="font-size:14px;opacity:0.85;margin-bottom:6px;">
Grupos Actuales
</div>

<div style="font-size:36px;font-weight:700;">
{{ $gruposExistentes }}
</div>

</div>

</div>


@if($sinGrupo === 0)
<div style="
text-align:center;
padding:40px;
background:#f8fafc;
border-radius:12px;
color:#64748b;">

<p style="font-size:18px;margin-bottom:8px;">
✅ No hay postulantes pendientes de asignación.
</p>
<p style="margin:0;font-size:14px;">
Todos los postulantes habilitados ya tienen grupo asignado.
</p>

</div>
@else
<div style="
background:#fffbeb;
border:1px solid #fcd34d;
border-radius:10px;
padding:16px;
margin-bottom:20px;">

<h4 style="margin:0 0 8px;color:#92400e;">📐 Regla de Asignación</h4>

<p style="margin:0;color:#78350f;font-size:14px;line-height:1.6;">
<strong>Capacidad máxima por grupo:</strong> 70 estudiantes<br>
<strong>Grupos necesarios:</strong> CEIL(Habilitados sin grupo / 70)<br>
<strong>Estrategia:</strong> Distribución secuencial, creando nuevos grupos si los existentes se llenan.
</p>

</div>


<form
method="POST"
action="{{ route('asignacion.ejecutar') }}"
onsubmit="return confirm('¿Estás seguro? Se asignarán {{ $sinGrupo }} postulantes en {{ $gruposNecesarios }} grupo(s).')"
style="text-align:center;">

@csrf

<button
type="submit"
style="
padding:16px 40px;
background:linear-gradient(135deg,#6d28d9,#7c3aed);
color:white;
border:none;
border-radius:12px;
font-size:17px;
font-weight:700;
cursor:pointer;
box-shadow:0 4px 14px rgba(124,58,237,0.4);
transition:0.2s;"
onmouseover="this.style.transform='scale(1.03)'"
onmouseout="this.style.transform='scale(1)'">

⚙️ Ejecutar Asignación de Grupos

</button>

</form>
@endif

</div>

@endsection
