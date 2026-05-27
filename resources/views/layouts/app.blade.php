<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>@yield('title')</title>

<style>

/* ===== RESET ===== */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: 'Segoe UI', Arial, sans-serif;
    background:#f1f5f9;
    display:flex;
}

/* ===== SIDEBAR ===== */
.sidebar{
    width:260px;
    height:100vh;
    background:#0f172a;
    color:white;
    position:fixed;
    left:0;
    top:0;
    padding:20px;
    transition:0.3s ease;
    overflow-y:auto;
    box-shadow:2px 0 10px rgba(0,0,0,0.2);
}

.logo{
    text-align:center;
    margin-bottom:30px;
    font-size:18px;
    font-weight:bold;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:6px 0;
}

.sidebar ul li a{
    text-decoration:none;
    color:#cbd5e1;
    display:block;
    padding:12px;
    border-radius:10px;
    transition:0.2s;
    font-size:15px;
}

.sidebar ul li a:hover{
    background:#1e293b;
    color:white;
    transform:translateX(4px);
}

/* ===== MAIN ===== */
.main{
    margin-left:260px;
    width:100%;
    transition:0.3s ease;
}

/* HEADER */
.header{
    background:white;
    padding:18px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
    position:sticky;
    top:0;
    z-index:10;
}

.header h2{
    font-size:20px;
    color:#0f172a;
}

.user-info{
    font-size:14px;
    color:#475569;
}

/* CONTENT */
.content{
    padding:25px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.2s;
}

.card:hover{
    transform:translateY(-2px);
}

/* ===== BOTÓN MOBILE ===== */
.menu-btn{
    display:none;
    font-size:26px;
    background:none;
    border:none;
    cursor:pointer;
    color:#0f172a;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px){

    .sidebar{
        transform:translateX(-100%);
        position:fixed;
        z-index:999;
    }

    .sidebar.active{
        transform:translateX(0);
    }

    .main{
        margin-left:0;
    }

    .menu-btn{
        display:block;
    }
}

</style>

</head>

<body>

@include('partials.sidebar')

<div class="main">

    <div class="header">

        <button class="menu-btn" onclick="toggleSidebar()">☰</button>

        <h2>@yield('header')</h2>

        <div class="user-info">
            {{ auth()->user()->name }}
            ({{ auth()->user()->role->name }})
        </div>

    </div>

    <div class="content">

        @yield('content')

    </div>

</div>

<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('active');
}
</script>

</body>

</html>