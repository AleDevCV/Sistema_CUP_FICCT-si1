@extends('layouts.app')

@section('title','Dashboard')

@section('header','Panel Principal — ' . auth()->user()->name)

@section('content')

{{-- ===== SECCIÓN POSTULANTE ===== --}}
@hasrole('Postulante')
<div class="card">

    <h2>Bienvenido {{ auth()->user()->name }}</h2>
    <br>
    <p>Has iniciado sesión correctamente.</p>
    <br>

    <div style="
        background:#fef3c7;
        border:1px solid #f59e0b;
        border-radius:10px;
        padding:20px;
        margin-bottom:20px;">

        <h3 style="margin:0 0 10px;">Estado de Pago</h3>

        @php
            $postulante = auth()->user()->postulante;
            $pago = $postulante?->pago;
        @endphp

        @if($postulante && $postulante->estado_final === 'HABILITADO')
            <div style="
                background:#dcfce7;
                color:#166534;
                padding:12px;
                border-radius:8px;
                font-weight:bold;">
                ✅ Tu matrícula ha sido confirmada. Estado: Habilitado.
            </div>
        @else
            <div style="
                background:#fef9c3;
                color:#854d0e;
                padding:12px;
                border-radius:8px;
                margin-bottom:15px;">
                ⏳ Tu pago está pendiente. Completa el proceso para habilitar tu inscripción.
            </div>

            <form method="POST" action="{{ route('pagos.checkout') }}">
                @csrf
                <button style="
                    background:#2563eb;
                    color:white;
                    padding:12px 25px;
                    border:none;
                    border-radius:8px;
                    font-size:16px;
                    cursor:pointer;">
                    💳 Pagar ahora con Stripe
                </button>
            </form>
        @endif
    </div>

</div>
@endhasrole


{{-- ===== SECCIÓN ADMINISTRATIVA (CU17) ===== --}}
@hasanyrole('Administrador|Coordinador|Autoridad')

<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:16px;
margin-bottom:24px;">

{{-- Total Inscritos --}}
<div style="
background:linear-gradient(135deg,#1e40af,#3b82f6);
color:white;
padding:20px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;">
Total Inscritos
</div>

<div style="font-size:32px;font-weight:700;margin-top:6px;">
{{ $totalInscritos }}
</div>

</div>


{{-- Habilitados --}}
<div style="
background:linear-gradient(135deg,#0891b2,#06b6d4);
color:white;
padding:20px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;">
Habilitados
</div>

<div style="font-size:32px;font-weight:700;margin-top:6px;">
{{ $totalHabilitados }}
</div>

</div>


{{-- Aprobados --}}
<div style="
background:linear-gradient(135deg,#15803d,#16a34a);
color:white;
padding:20px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;">
Aprobados
</div>

<div style="font-size:32px;font-weight:700;margin-top:6px;">
{{ $totalAprobados }}
</div>

</div>


{{-- Reprobados --}}
<div style="
background:linear-gradient(135deg,#b91c1c,#dc2626);
color:white;
padding:20px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;">
Reprobados
</div>

<div style="font-size:32px;font-weight:700;margin-top:6px;">
{{ $totalReprobados }}
</div>

</div>


{{-- Grupos --}}
<div style="
background:linear-gradient(135deg,#7c3aed,#a855f7);
color:white;
padding:20px;
border-radius:14px;
text-align:center;">

<div style="font-size:13px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;">
Grupos Activos
</div>

<div style="font-size:32px;font-weight:700;margin-top:6px;">
{{ $totalGrupos }}
</div>

</div>

</div>


{{-- Tasa de Aprobación --}}
<div style="
background:white;
border:1px solid #e2e8f0;
border-radius:14px;
padding:24px;
margin-bottom:24px;">

<h3 style="margin:0 0 16px;color:#0f172a;font-size:16px;">
📊 Tasa de Aprobación
</h3>

<div style="display:flex;align-items:center;gap:16px;">

<div style="flex:1;">
<div style="
background:#e2e8f0;
border-radius:10px;
height:24px;
overflow:hidden;">

<div style="
width:{{ $tasaAprobacion }}%;
height:100%;
background:linear-gradient(90deg,
{{ $tasaAprobacion >= 50 ? '#16a34a,#22c55e' : '#dc2626,#ef4444' }});
border-radius:10px;
transition:width 0.5s ease;">

</div>

</div>
</div>

<div style="font-size:24px;font-weight:700;color:{{ $tasaAprobacion >= 50 ? '#16a34a' : '#dc2626' }};">
{{ $tasaAprobacion }}%
</div>

</div>

</div>


{{-- Acciones Rápidas --}}
<div style="
background:white;
border:1px solid #e2e8f0;
border-radius:14px;
padding:24px;
margin-bottom:24px;">

<h3 style="margin:0 0 16px;color:#0f172a;font-size:16px;">
⚡ Acciones Rápidas
</h3>

<div style="display:flex;flex-wrap:wrap;gap:10px;">

@hasrole('Administrador')
<a href="{{ route('cierre.index') }}" style="
padding:10px 18px;
background:linear-gradient(135deg,#b91c1c,#dc2626);
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
🔒 Cierre Académico
</a>

<a href="{{ route('asignacion.index') }}" style="
padding:10px 18px;
background:linear-gradient(135deg,#6d28d9,#7c3aed);
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
⚙️ Asignación de Grupos
</a>

<a href="{{ route('reasignacion.index') }}" style="
padding:10px 18px;
background:linear-gradient(135deg,#0891b2,#06b6d4);
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
🏆 Reasignación de Cupos
</a>

<a href="{{ route('auditorias.index') }}" style="
padding:10px 18px;
background:#475569;
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
🔍 Auditoría
</a>
@endhasrole

@hasrole('Coordinador')
<a href="{{ route('docentes.index') }}" style="
padding:10px 18px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
👨‍🏫 Docentes
</a>

<a href="{{ route('asignaciones.index') }}" style="
padding:10px 18px;
background:#7c3aed;
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
🔗 Asignaciones
</a>

<a href="{{ route('examenes.index') }}" style="
padding:10px 18px;
background:#059669;
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
📝 Exámenes
</a>

<a href="{{ route('postulantes.index') }}" style="
padding:10px 18px;
background:#ea580c;
color:white;
text-decoration:none;
border-radius:8px;
font-size:14px;">
📋 Postulantes
</a>
@endhasrole

</div>

</div>

@endhasanyrole

@endsection