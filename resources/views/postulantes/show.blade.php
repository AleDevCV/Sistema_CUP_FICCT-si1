@extends('layouts.app')

@section('title','Detalle Postulante')

@section('header','Detalle Postulante')

@section('content')

<div class="card">

<h2>

{{ $postulante->nombre_completo }}

</h2>

<br>

<p>
<strong>CI:</strong>
{{ $postulante->ci }}
</p>

<p>
<strong>Email:</strong>
{{ $postulante->email }}
</p>

<p>
<strong>Teléfono:</strong>
{{ $postulante->telefono }}
</p>

<p>
<strong>Colegio:</strong>
{{ $postulante->colegio }}
</p>

<p>
<strong>Primera carrera:</strong>

{{ $postulante->primeraCarrera?->nombre }}

</p>

<p>
<strong>Segunda carrera:</strong>

{{ $postulante->segundaCarrera?->nombre }}

</p>

<p>
<strong>Carrera Admitida:</strong>

@if($postulante->estado_final === 'Aprobado sin Cupo')
<span style="
padding:4px 10px;
background:#fef3c7;
color:#92400e;
border-radius:12px;
font-size:13px;">
Sin cupo disponible
</span>
@else
<span style="color:#0891b2;font-weight:600;">
{{ $postulante->carreraAdmitida?->nombre ?? 'Pendiente de asignación' }}
</span>
@endif

</p>

<p>
<strong>Grupo:</strong>

{{ $postulante->grupo?->nombre }}

</p>

<br>

<div style="
background:#f8fafc;
border:1px solid #e2e8f0;
border-radius:10px;
padding:20px;
margin-bottom:20px;">

<h3 style="margin:0 0 15px;">Detalles de Pago</h3>

@if($postulante->pago)

<p>
<strong>Estado:</strong>

@if($postulante->pago->estado === 'PAGADO')
<span style="
padding:4px 10px;
background:#dcfce7;
color:#166534;
border-radius:12px;
font-size:13px;">
Pagado
</span>

@elseif($postulante->pago->estado === 'RECHAZADO')
<span style="
padding:4px 10px;
background:#fee2e2;
color:#991b1b;
border-radius:12px;
font-size:13px;">
Rechazado
</span>

@else
<span style="
padding:4px 10px;
background:#fef3c7;
color:#92400e;
border-radius:12px;
font-size:13px;">
Pendiente
</span>

@endif

</p>

<p>
<strong>Monto:</strong>
{{ $postulante->pago->monto }} BOB
</p>

<p>
<strong>Método:</strong>
{{ $postulante->pago->metodo_pago }}
</p>

<p>
<strong>Fecha de registro:</strong>
{{ $postulante->pago->created_at->format('d/m/Y') }}
</p>

@else

<p style="color:#6b7280;">
No se ha registrado ningún pago.
</p>

@endif

</div>

<a
href="{{ route('postulantes.index') }}">

Volver

</a>

</div>

@endsection