@extends('layouts.app')

@section('title','Dashboard')

@section('header','Panel principal')

@section('content')

<div class="card">

    <h2>

        Bienvenido {{ auth()->user()->name }}

    </h2>

    <br>

    <p>

        Has iniciado sesión correctamente.

    </p>

    <br>

    @if(auth()->user()->hasRole('Postulante'))
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
    @endif

    <form
    method="POST"
    action="{{ route('logout') }}">

        @csrf

        <button
        class="logout-btn">

            Cerrar sesión

        </button>

    </form>

</div>

@endsection