<div class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <h2>Mi Sistema</h2>

        <button class="close-btn" onclick="toggleSidebar()">✕</button>
    </div>

    <ul class="menu">

        <li>
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('perfil.edit') }}">
                <i class="fa-solid fa-user-gear"></i>
                <span>Mi Perfil</span>
            </a>
        </li>

        @role('Postulante')
        <li>
            <a href="{{ route('postulante.panel') }}">
                <i class="fa-solid fa-id-card"></i>
                <span>Mi Panel Estudiantil</span>
            </a>
        </li>
        @endrole

        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
        <li>
            <a href="{{ route('users.index') }}">
                <i class="fa-solid fa-user"></i>
                <span>Usuarios</span>
            </a>
        </li>
        @endif

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('carreras.index') }}">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Carreras</span>
            </a>
        </li>
        @endhasanyrole

        @role('Administrador')
        <li>
            <a href="{{ route('roles.index') }}">
                <i class="fa-solid fa-key"></i>
                <span>Roles</span>
            </a>
        </li>
        @endrole

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('postulantes.index') }}">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Postulantes</span>
            </a>
        </li>
        @endhasanyrole

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('materias.index') }}">
                <i class="fa-solid fa-book"></i>
                <span>Materias</span>
            </a>
        </li>
        @endhasanyrole

        <li>
            <a href="{{ route('examenes.index') }}">
                <i class="fa-solid fa-file-pen"></i>
                <span>Exámenes</span>
            </a>
        </li>

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('cupos.index') }}">
                <i class="fa-solid fa-layer-group"></i>
                <span>Cupos</span>
            </a>
        </li>
        @endhasanyrole

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('docentes.index') }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Docentes</span>
            </a>
        </li>
        @endhasanyrole

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('asignaciones.index') }}">
                <i class="fa-solid fa-link"></i>
                <span>Asignaciones</span>
            </a>
        </li>
        @endhasanyrole

        @hasanyrole('Administrador|Coordinador')
        <li>
            <a href="{{ route('grupos.index') }}">
                <i class="fa-solid fa-users"></i>
                <span>Grupos</span>
            </a>
        </li>
        @endhasanyrole

        @role('Administrador')
        <li>
            <a href="{{ route('asignacion.index') }}">
                <i class="fa-solid fa-people-arrows"></i>
                <span>Asignación Automática</span>
            </a>
        </li>
        @endrole

        @role('Administrador')
        <li>
            <a href="{{ route('cierre.index') }}">
                <i class="fa-solid fa-lock"></i>
                <span>Cierre Académico</span>
            </a>
        </li>
        @endrole

        @role('Administrador')
        <li>
            <a href="{{ route('reasignacion.index') }}">
                <i class="fa-solid fa-trophy"></i>
                <span>Reasignación de Cupos</span>
            </a>
        </li>
        @endrole

        @hasanyrole('Administrador|Coordinador|Autoridad')
        <li>
            <a href="{{ route('reportes.general') }}">
                <i class="fa-solid fa-file-alt"></i>
                <span>Reportes Generales</span>
            </a>
        </li>
        @endhasanyrole

        @role('Administrador')
        <li>
            <a href="{{ route('auditorias.index') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Auditoría</span>
            </a>
        </li>
        @endrole

    </ul>

</div>

<script>
function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("active");
}
</script>

<style>

/* ===== SIDEBAR ===== */
.sidebar {
    width: 260px;
    height: 100vh;
    background: #0f172a;
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    padding: 20px;
    transition: 0.3s ease;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0,0,0,0.2);
}

/* HEADER */
.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.sidebar-header h2 {
    font-size: 18px;
}

/* BOTÓN CERRAR (MÓVIL) */
.close-btn {
    display: none;
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

/* MENU */
.menu {
    list-style: none;
    padding: 0;
}

.menu li {
    margin: 6px 0;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    color: #cbd5e1;
    text-decoration: none;
    transition: 0.2s;
    font-size: 15px;
}

.menu a i {
    width: 20px;
    text-align: center;
}

.menu a:hover {
    background: #1e293b;
    color: white;
    transform: translateX(5px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {

    .sidebar {
        transform: translateX(-100%);
        z-index: 1000;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .close-btn {
        display: block;
    }
}

</style>