{{-- resources/views/auth/forgot-password.blade.php --}}
{{-- Formulario para solicitar enlace de recuperación de contraseña --}}
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Recuperar Contraseña - CUP</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    height: 100vh;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.login-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255,255,255,0.95);
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
    animation: fadeIn 0.6s ease-in-out;
}

/* HEADER */
.login-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #0f172a;
}

.login-subtitle {
    text-align: center;
    font-size: 14px;
    color: #64748b;
    margin-bottom: 25px;
}

/* INPUTS */
.form-control {
    border-radius: 10px;
    padding: 12px;
    border: 1px solid #e2e8f0;
}

.form-control:focus {
    box-shadow: none;
    border-color: #2563eb;
}

/* BUTTON */
.btn-login {
    background: #2563eb;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    transition: 0.2s;
}

.btn-login:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

/* ANIMATION */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}

</style>

</head>

<body>

<div class="login-card">

    <div class="login-title">
        Recuperar Contraseña
    </div>

    <p class="login-subtitle">
        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">

        @csrf

        <div class="mb-3">

            <label class="form-label">Email</label>

            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="tucorreo@ejemplo.com"
                   value="{{ old('email') }}"
                   required>

            @error('email')
                <div class="alert alert-danger mt-2 mb-0 py-2">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-login w-100 text-white">
            Enviar enlace de recuperación
        </button>

    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" style="text-decoration: none; color: #2563eb;">
            Volver al inicio de sesión
        </a>
    </div>

</div>

</body>

</html>
