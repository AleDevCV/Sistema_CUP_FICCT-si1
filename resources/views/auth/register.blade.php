<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>

    <style>
        body{
            font-family:Arial,sans-serif;
            max-width:400px;
            margin:50px auto;
        }

        .error{
            color:red;
            margin-bottom:10px;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:10px;
        }

        button{
            width:100%;
            padding:10px;
        }
    </style>

</head>
<body>

<h2>Crear cuenta</h2>

@if($errors->any())

    <div class="error">

        <ul>

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

<form action="{{ route('register') }}" method="POST">

    @csrf

    <input
        type="text"
        name="name"
        placeholder="Nombre"
        value="{{ old('name') }}"
    >

    <input
        type="text"
        name="username"
        placeholder="Nombre de usuario"
        value="{{ old('username') }}"
    >

    <input
        type="email"
        name="email"
        placeholder="Correo"
        value="{{ old('email') }}"
    >

    <input
        type="password"
        name="password"
        placeholder="Contraseña"
    >

    <input
        type="password"
        name="password_confirmation"
        placeholder="Confirmar contraseña"
    >

    <button type="submit">
        Registrarse
    </button>

</form>

<p>
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}">
        Iniciar sesión
    </a>
</p>

</body>
</html>