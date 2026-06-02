<?php
/* ================================================
   Acceso_Denegado.php
   Se muestra cuando un Usuario intenta entrar
   a una página solo para Administradores.
   ================================================ */
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&display=swap');

    .denegado-wrap {
        font-family: 'Sora', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        padding: 40px 20px;
    }

    .denegado-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,.10);
        padding: 52px 44px;
        text-align: center;
        max-width: 480px;
        width: 100%;
        animation: fadeUp .5s ease both;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(24px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .denegado-icon-wrap {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: linear-gradient(135deg, #be123c, #f43f5e);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 28px;
        box-shadow: 0 6px 24px rgba(190,18,60,.30);
    }

    .denegado-icon-wrap i {
        font-size: 42px;
        color: #fff;
    }

    .denegado-card h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .denegado-card p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 32px;
    }

    .denegado-badge {
        display: inline-block;
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 22px;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .btn-volver {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        color: #fff !important;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 14px rgba(29,78,216,.35);
    }

    .btn-volver:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29,78,216,.40);
        color: #fff !important;
        text-decoration: none;
    }
</style>

<section class="content">
    <div class="denegado-wrap">
        <div class="denegado-card">

            <div class="denegado-icon-wrap">
                <i class="fa fa-lock"></i>
            </div>

            <span class="denegado-badge">Acceso Restringido</span>

            <h2>No tienes permiso</h2>

            <p>
                Esta sección es exclusiva para <strong>Administradores</strong>.<br>
                Tu cuenta de
                <strong><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></strong>
                no tiene los permisos necesarios para acceder aquí.
            </p>

            <a href="index.php" class="btn-volver">
                <i class="fa fa-home"></i>
                Volver al Inicio
            </a>

        </div>
    </div>
</section>
