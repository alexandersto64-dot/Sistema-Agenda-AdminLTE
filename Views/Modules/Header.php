<header class="main-header">

<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/* =========================
   VALIDACIÓN DE SESIÓN
========================= */

if(!isset($_SESSION["usuario"])){
    header("Location: index.php");
    exit;
}

/* =========================
   DATOS SEGURIDAD
========================= */

$foto = $_SESSION["foto"] ?? "Views/Images/Users/default.jpg";
$foto .= "?v=" . time();

$nombre = $_SESSION["nombre"] ?? "";
$rol    = $_SESSION["rol"] ?? "";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');

    /* ── MAIN HEADER ── */
    .main-header {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        height: 56px !important;
        /* Mismo azul que el dashboard */
        background: linear-gradient(90deg, #1565c0 0%, #1e88e5 100%) !important;
        border-bottom: none !important;
        box-shadow: 0 2px 12px rgba(21,101,192,0.25) !important;
    }

    /* ── LOGO (sidebar colapsado y expandido) ── */
    .main-header .logo {
        height: 56px !important;
        line-height: 56px !important;
        background: rgba(0,0,0,0.18) !important;
        border-bottom: none !important;
        padding: 0 16px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        transition: background 0.2s !important;
    }

    .main-header .logo:hover {
        background: rgba(0,0,0,0.25) !important;
        text-decoration: none !important;
    }

    /* Caja del ícono del logo */
    .main-header .logo .logo-icon-wrap {
        width: 30px;
        height: 30px;
        background: rgba(255,255,255,0.20);
        border: 1px solid rgba(255,255,255,0.30);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .main-header .logo .logo-icon-wrap i {
        color: #fff;
        font-size: 15px;
    }

    /* Texto logo expandido */
    .main-header .logo .logo-lg {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        letter-spacing: -0.3px;
        line-height: 1;
    }

    .main-header .logo .logo-lg b {
        color: #bbdefb !important;
        font-weight: 600 !important;
    }

    /* Logo mini (sidebar colapsado) — era blanco en blanco, ahora visible */
    .main-header .logo .logo-mini {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .main-header .logo .logo-mini b {
        color: #bbdefb !important;
    }

    /* ── NAVBAR ── */
    .main-header .navbar {
        height: 56px !important;
        min-height: 56px !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        margin-left: 0 !important;
    }

    /* Sidebar toggle */
    .main-header .sidebar-toggle {
        height: 56px !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 18px !important;
        color: rgba(255,255,255,0.85) !important;
        font-size: 20px !important;
        transition: color 0.15s, background 0.15s !important;
    }

    .main-header .sidebar-toggle:hover {
        color: #ffffff !important;
        background: rgba(255,255,255,0.10) !important;
    }

    /* ── MENÚ DERECHO ── */
    .main-header .navbar-custom-menu > .nav > li > a {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        height: 56px !important;
        padding: 0 14px !important;
        color: rgba(255,255,255,0.90) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: background 0.15s !important;
    }

    .main-header .navbar-custom-menu > .nav > li > a:hover {
        background: rgba(255,255,255,0.12) !important;
        color: #ffffff !important;
    }

    /* Foto de usuario en navbar */
    .main-header .user-image {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        border: 2px solid rgba(255,255,255,0.45) !important;
        flex-shrink: 0;
    }

    /* Flecha dropdown */
    .main-header .user.dropdown > a::after {
        content: '';
        display: inline-block;
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid rgba(255,255,255,0.70);
        margin-left: 2px;
        flex-shrink: 0;
        vertical-align: middle;
    }

    /* ── DROPDOWN MENU — MÁS CLARO ── */
    .main-header .dropdown-menu {
        border: 1px solid #e0e9f5 !important;
        border-radius: 14px !important;
        box-shadow: 0 8px 32px rgba(21,101,192,0.15) !important;
        padding: 0 !important;
        overflow: hidden !important;
        min-width: 230px !important;
        top: calc(100% + 6px) !important;
        right: 0 !important;
        left: auto !important;
        background: #ffffff !important;
    }

    /* Header del dropdown — CLARO (antes era oscuro) */
    .main-header .user-header {
        background: #e3f2fd !important;  /* azul muy claro */
        padding: 1.1rem 1.25rem 1rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        border-bottom: 1px solid #bbdefb !important;
    }

    .main-header .user-header .img-circle {
        width: 46px !important;
        height: 46px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        border: 2.5px solid #90caf9 !important;
        flex-shrink: 0;
    }

    .main-header .user-header p {
        margin: 0 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #1a2340 !important;   /* texto oscuro sobre fondo claro */
        line-height: 1.3 !important;
    }

    .main-header .user-header p small {
        display: block !important;
        font-size: 11px !important;
        font-weight: 400 !important;
        color: #5c7a99 !important;
        margin-top: 2px !important;
    }

    /* Footer del dropdown */
    .main-header .user-footer {
        background: #f7f9fc !important;
        padding: 0.7rem 1rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        border-top: 1px solid #e8ecf0 !important;
    }

    /* Botón Perfil */
    .main-header .user-footer .btn-primary.btn-flat {
        background: #1e88e5 !important;
        border: none !important;
        border-radius: 8px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #fff !important;
        padding: 7px 16px !important;
        transition: background 0.15s !important;
    }

    .main-header .user-footer .btn-primary.btn-flat:hover {
        background: #1565c0 !important;
    }

    /* Botón Cerrar Sesión */
    .main-header .user-footer .btn-danger.btn-flat {
        background: transparent !important;
        border: 1.5px solid #f5c6c6 !important;
        border-radius: 8px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #c0392b !important;
        padding: 7px 16px !important;
        transition: all 0.15s !important;
    }

    .main-header .user-footer .btn-danger.btn-flat:hover {
        background: #c0392b !important;
        border-color: #c0392b !important;
        color: #fff !important;
    }

    /* ── SIDEBAR — logo-mini visible al colapsar ── */
    /* Cuando el body tiene sidebar-collapse el .logo muestra logo-mini */
    .sidebar-collapse .main-header .logo .logo-mini {
        display: block !important;
        color: #ffffff !important;
    }

    .sidebar-collapse .main-header .logo .logo-icon-wrap {
        display: none !important;
    }

    /* Ajuste body */
    body {
        padding-top: 0 !important;
    }

    .main-sidebar {
        padding-top: 56px;
    }
</style>

<!-- LOGO -->
<a href="index.php" class="logo">

    <div class="logo-icon-wrap">
        <i class="fa fa-calendar"></i>
    </div>

    <span class="logo-lg"><b>Agenda</b>2026</span>

    <span class="logo-mini"><b>A</b>26</span>

</a>

<!-- NAVBAR -->
<nav class="navbar navbar-static-top">

    <!-- SIDEBAR TOGGLE -->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">

            <!-- USER DROPDOWN -->
            <li class="dropdown user user-menu">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                    <img src="<?= $foto ?>"
                         class="user-image"
                         alt="Usuario">

                    <span class="hidden-xs">
                        <?= htmlspecialchars($nombre) ?>
                    </span>

                </a>

                <!-- DROPDOWN MENU -->
                <ul class="dropdown-menu">

                    <!-- USER HEADER -->
                    <li class="user-header">

                        <img src="<?= $foto ?>"
                             class="img-circle"
                             alt="Usuario">

                        <p>
                            <?= htmlspecialchars($nombre) ?>
                            <small><?= htmlspecialchars($rol) ?></small>
                        </p>

                    </li>

                    <!-- FOOTER -->
                    <li class="user-footer">

                        <div class="pull-left">
                            <a href="#"
                               class="btn btn-primary btn-flat"
                               data-toggle="modal"
                               data-target="#modalPerfil">
                                Perfil
                            </a>
                        </div>

                        <div class="pull-right">
                            <a href="logout.php"
                               class="btn btn-danger btn-flat">
                                Cerrar Sesión
                            </a>
                        </div>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>

</header>