<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/* =========================
   CREAR TOKEN CSRF
========================= */

if(empty($_SESSION["token"])){
    $_SESSION["token"] = bin2hex(random_bytes(32));
}

/* =========================
   CONTROLLER PRIMERO
   (antes de cualquier output)
========================= */

require_once "Controllers/login.Controller.php";

$login = new LoginController();
$login->ctrIngreso();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda 2026 — Iniciar Sesión</title>

    <!-- Bootstrap / AdminLTE -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body.login-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            /* Gradiente igual al hero del dashboard */
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 50%, #42a5f5 100%);
        }

        /* Círculos decorativos de fondo */
        body.login-page::before {
            content: '';
            position: fixed;
            top: -15%;
            right: -10%;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            pointer-events: none;
        }

        body.login-page::after {
            content: '';
            position: fixed;
            bottom: -20%;
            left: -8%;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }

        /* ── WRAPPER ── */
        .login-box {
            width: 100%;
            max-width: 390px;
            margin: 0 auto;
            padding: 1rem;
            position: relative;
            z-index: 1;
        }

        /* ── LOGO ── */
        .login-logo {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .login-logo .logo-inner {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .login-logo .logo-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.20);
            border: 1.5px solid rgba(255,255,255,0.35);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
        }

        .login-logo .logo-text {
            font-size: 26px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .login-logo .logo-text b {
            color: #bbdefb;
        }

        .login-logo .logo-sub {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: rgba(255,255,255,0.65);
            letter-spacing: 0.08em;
            text-align: center;
            margin-top: 4px;
        }

        /* ── CARD ── */
        .login-box-body {
            background: #ffffff;
            border-radius: 18px;
            padding: 2.25rem 2rem 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.20);
            border: none;
        }

        .login-box-msg {
            text-align: center;
            font-size: 17px;
            font-weight: 600;
            color: #1a2340;
            margin-bottom: 0.35rem;
        }

        .login-box-sub {
            text-align: center;
            font-size: 13px;
            color: #8a94a6;
            margin-bottom: 1.75rem;
        }

        /* ── CAMPOS ── */
        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 5px;
            display: block;
            letter-spacing: 0.03em;
        }

        .form-group .form-control {
            height: 46px;
            padding: 0 14px 0 42px;
            border: 1.5px solid #e8ecf0;
            border-radius: 10px;
            background: #f7f8fc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: #1a2340;
            box-shadow: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .form-group .form-control::placeholder { color: #b0b8c8; }

        .form-group .form-control:focus {
            border-color: #1e88e5;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(30,136,229,0.12);
            outline: none;
        }

        /* Íconos */
        .form-group .form-control-feedback {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            right: auto;
            width: auto;
            height: auto;
            line-height: 1;
            font-size: 16px;
            color: #b0b8c8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-group .form-control:focus ~ .form-control-feedback {
            color: #1e88e5;
        }

        /* ── BOTÓN ── */
        .btn-primary.btn-block {
            height: 46px;
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #fff;
            margin-top: 0.75rem;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(21,101,192,0.35);
        }

        .btn-primary.btn-block:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(21,101,192,0.42);
        }

        .btn-primary.btn-block:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(21,101,192,0.25);
        }

        /* ── ALERTAS ── */
        .alert {
            border-radius: 10px;
            font-size: 13px;
            border: none;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background: #fff0f0;
            color: #c0392b;
        }

        /* Pie del card */
        .login-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 12px;
            color: #b0b8c8;
        }
    </style>
</head>
<body class="login-page">

<div class="login-box">

    <div class="login-logo">
        <div class="logo-inner">
            <div class="logo-icon">
                <i class="fa fa-calendar"></i>
            </div>
            <span class="logo-text"><b>Agenda</b>2026</span>
        </div>
        <span class="logo-sub">Sistema de Gestión de Contactos</span>
    </div>

    <div class="login-box-body">

        <p class="login-box-msg">Bienvenido</p>
        <p class="login-box-sub">Ingresa tus credenciales para continuar</p>

        <form method="POST">

            <!-- TOKEN CSRF -->
            <input type="hidden"
                   name="token"
                   value="<?= $_SESSION["token"] ?>">

            <!-- USUARIO -->
            <div class="form-group has-feedback">
                <input type="text"
                       class="form-control"
                       name="usuario"
                       placeholder="Usuario"
                       required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <!-- PASSWORD -->
            <div class="form-group has-feedback">
                <input type="password"
                       class="form-control"
                       name="password"
                       placeholder="Contraseña"
                       required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <button type="submit"
                    class="btn btn-primary btn-block">
                <i class="fa fa-sign-in" style="margin-right:6px;"></i>
                Ingresar
            </button>

        </form>

        <div class="login-footer">Agenda 2026 &copy; <?= date('Y') ?></div>

    </div>
</div>

</body>
</html>