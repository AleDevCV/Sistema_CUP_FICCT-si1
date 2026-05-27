<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login CUP</title>

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

/* CARD LOGIN */
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
    margin-bottom: 25px;
    color: #0f172a;
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

/* ERROR */
.text-danger {
    font-size: 13px;
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
        Sistema CUP
    </div>

    <form method="POST" action="/login">

        @csrf

        <div class="mb-3">

            <label class="form-label">Email</label>

            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ old('email') }}"
                   required>

            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>

        <div class="mb-3">

            <label class="form-label">Contraseña</label>

            <input type="password"
                   name="password"
                   class="form-control"
                   required>

        </div>

        <button class="btn btn-login w-100 text-white">
            Ingresar
        </button>

    </form>

</div>

</body>

</html>