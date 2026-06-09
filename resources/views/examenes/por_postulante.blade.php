@extends('layouts.app')

@section('title','Exámenes — ' . $postulante->nombre_completo)

@section('header','Exámenes de ' . $postulante->nombre_completo)

@section('content')

<div class="card">

<div style="
display:flex;
justify-content:space-between;
margin-bottom:20px;">

<div>
<a
href="{{ route('examenes.index') }}"
style="
padding:8px 16px;
background:#64748b;
color:white;
text-decoration:none;
border-radius:6px;">

← Volver a Postulantes

</a>
</div>

</div>


@if(session('success'))
<div style="
background:#dcfce7;
color:#166534;
padding:10px;
margin-bottom:15px;
border-radius:8px;">
{{ session('success') }}
</div>
@endif


@if(session('error'))
<div style="
background:#fee2e2;
color:#991b1b;
padding:10px;
margin-bottom:15px;
border-radius:8px;">
{{ session('error') }}
</div>
@endif


@forelse($examenesPorMateria as $bloque)

<div style="
border:1px solid #e2e8f0;
border-radius:12px;
margin-bottom:24px;
overflow:hidden;">

<div style="
padding:16px 20px;
background:linear-gradient(135deg, #1e40af, #3b82f6);
color:white;">

<h3 style="margin:0;font-size:18px;">

{{ $bloque['materia']->nombre }}

</h3>

@if($bloque['materia']->descripcion)
<p style="margin:4px 0 0;font-size:13px;opacity:0.85;">
{{ $bloque['materia']->descripcion }}
</p>
@endif

</div>


<table
width="100%"
cellpadding="10"
style="border-collapse:collapse;">

<thead>

<tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">

<th style="text-align:left;">Examen #</th>
<th style="text-align:left;">Nota</th>
<th style="text-align:left;">Peso (%)</th>
<th style="text-align:left;">Ponderación</th>
<th style="text-align:left;">Acción</th>

</tr>

</thead>

<tbody>

@foreach($bloque['examenes'] as $examen)

<tr style="border-bottom:1px solid #f1f5f9;">

<td>

<span style="
background:#e0e7ff;
color:#1e40af;
padding:4px 10px;
border-radius:4px;
font-weight:600;">

Examen {{ $examen->numero_examen }}

</span>

</td>

<td>

<span style="
font-weight:600;
font-size:16px;
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

<td>

<a
href="{{ route('examenes.edit', $examen->id) }}"
style="
padding:6px 14px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:6px;
display:inline-block;
font-size:13px;">

Editar

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@empty

<div style="
text-align:center;
padding:60px 20px;
color:#64748b;">

<p style="font-size:18px;margin-bottom:8px;">

No se encontraron exámenes para este postulante.

</p>

<a
href="{{ route('examenes.create') }}"
style="
padding:10px 20px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;">

Registrar Examen

</a>

</div>

@endforelse

</div>

@endsection
