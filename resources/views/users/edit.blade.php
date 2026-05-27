@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">

            <h3 class="mb-0">
                Editar Usuario
            </h3>

        </div>

        <div class="card-body">

            <form
                action="{{ route('users.update',$user) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                @include('users.form')

            </form>

        </div>

    </div>

</div>

@endsection