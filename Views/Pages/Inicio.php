<?php
require_once "Models/Conexion.php";

$pdo = Conexion::conectar();
$rol = $_SESSION["rol"];

/* ================================================
   DATOS SEGÚN ROL
   - Admin: ve estadísticas globales y todos los contactos
   - Usuario: ve solo un mensaje de bienvenida y sus datos
   ================================================ */

if ($rol === "Administrador") {

    $total_operadores = $pdo->query("SELECT COUNT(*) FROM operador")->fetchColumn();
    $total_empresas   = $pdo->query("SELECT COUNT(*) FROM empresa")->fetchColumn();
    $total_grupos     = $pdo->query("SELECT COUNT(*) FROM grupo_contacto")->fetchColumn();
    $total_contactos  = $pdo->query("SELECT COUNT(*) FROM contacto")->fetchColumn();

    $ultimos = $pdo->query("
        SELECT c.nombres, c.apellidos, c.telefono_movil, c.fecha_registro,
               e.nombre_empresa, g.nombre_grupo
        FROM contacto c
        JOIN empresa e        ON c.id_empresa  = e.id_empresa
        JOIN grupo_contacto g ON c.id_grupo    = g.id_grupo
        ORDER BY c.fecha_registro DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

} else {

    /* Usuario normal: estadísticas neutrales */
    $total_operadores = $pdo->query("SELECT COUNT(*) FROM operador")->fetchColumn();
    $total_empresas   = $pdo->query("SELECT COUNT(*) FROM empresa")->fetchColumn();
    $total_grupos     = $pdo->query("SELECT COUNT(*) FROM grupo_contacto")->fetchColumn();
    $total_contactos  = $pdo->query("SELECT COUNT(*) FROM contacto")->fetchColumn();

    /* Solo los últimos 5 contactos generales (sin botones de admin) */
    $ultimos = $pdo->query("
        SELECT c.nombres, c.apellidos, c.telefono_movil, c.fecha_registro,
               e.nombre_empresa, g.nombre_grupo
        FROM contacto c
        JOIN empresa e        ON c.id_empresa  = e.id_empresa
        JOIN grupo_contacto g ON c.id_grupo    = g.id_grupo
        ORDER BY c.fecha_registro DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&display=swap');

    .dash-wrap,
.dash-wrap p,
.dash-wrap h1,
.dash-wrap h2,
.dash-wrap h3,
.dash-wrap h4,
.dash-wrap h5,
.dash-wrap span,
.dash-wrap div,
.dash-wrap a,
.dash-wrap td,
.dash-wrap th {
    font-family: 'Sora', sans-serif;
}

    .dash-hero {
        background: linear-gradient(135deg, #1a2a4a 0%, #1d4ed8 60%, #0ea5e9 100%);
        border-radius: 14px;
        padding: 38px 40px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(29,78,216,.35);
    }
    .dash-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 260px; height: 260px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .dash-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; right: 80px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .dash-hero h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 6px;
        letter-spacing: -.3px;
    }
    .dash-hero p { font-size: 14px; opacity: .75; margin: 0; }
    .dash-hero .hero-icon {
        font-size: 64px;
        opacity: .15;
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
    }
    .dash-hero .badge-date {
        display: inline-block;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 20px;
        padding: 3px 14px;
        font-size: 12px;
        margin-bottom: 12px;
        backdrop-filter: blur(6px);
    }

    .stat-card {
        border-radius: 14px;
        padding: 24px 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        cursor: pointer;
        transition: transform .25s, box-shadow .25s;
        text-decoration: none !important;
        display: block;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 40px rgba(0,0,0,.2) !important;
        color: #fff;
    }
    .stat-card .sc-bg-icon {
        position: absolute;
        right: -10px; bottom: -10px;
        font-size: 80px;
        opacity: .12;
        transition: transform .3s;
    }
    .stat-card:hover .sc-bg-icon { transform: scale(1.15) rotate(-5deg); }
    .stat-card .sc-num {
        font-size: 48px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-card .sc-label {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: .85;
    }
    .stat-card .sc-sub { font-size: 12px; opacity: .65; margin-top: 2px; }
    .stat-card .sc-arrow {
        position: absolute;
        top: 20px; right: 20px;
        font-size: 16px;
        opacity: .6;
    }

    .sc-blue  { background: linear-gradient(135deg,#1d4ed8,#3b82f6); box-shadow: 0 6px 20px rgba(59,130,246,.4); }
    .sc-green { background: linear-gradient(135deg,#059669,#10b981); box-shadow: 0 6px 20px rgba(16,185,129,.4); }
    .sc-amber { background: linear-gradient(135deg,#d97706,#fbbf24); box-shadow: 0 6px 20px rgba(251,191,36,.4); }
    .sc-rose  { background: linear-gradient(135deg,#be123c,#f43f5e); box-shadow: 0 6px 20px rgba(244,63,94,.4); }

    .recent-box {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0,0,0,.07);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .recent-box .rb-header {
        padding: 16px 22px;
        background: linear-gradient(90deg,#1a2a4a,#1d4ed8);
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .recent-box .rb-header h4 { margin:0; font-size:15px; font-weight:600; }
    .recent-box table { margin:0; }
    .recent-box table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .8px;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        padding: 10px 14px;
    }
    .recent-box table tbody td {
        padding: 11px 14px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 13px;
    }
    .recent-box table tbody tr:hover { background: #f8fafc; }

    .avatar-circle {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: #fff;
        flex-shrink: 0;
    }
    .badge-grupo {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Accesos Rápidos */
    .acceso-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        border-radius: 10px;
        padding: 11px 16px;
        border: 2px solid #e2e8f0;
        font-weight: 600;
        color: #1e293b;
        background: #fff;
        text-decoration: none;
        transition: all .2s;
    }
    .acceso-btn:hover {
        border-color: #c7d2fe;
        background: #f8faff;
        color: #1e293b;
        transform: translateX(4px);
        text-decoration: none;
    }
    .acceso-btn .ab-icon {
        width: 34px; height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #fff;
        flex-shrink: 0;
    }
    .acceso-btn .ab-text { font-size: 13px; font-weight: 600; }
    .acceso-btn .ab-arrow {
        margin-left: auto;
        font-size: 14px;
        color: #94a3b8;
        transition: transform .2s;
    }
    .acceso-btn:hover .ab-arrow { transform: translateX(3px); }

    #cuerpoAccesos {
        transition: max-height .35s ease, opacity .35s ease;
        max-height: 500px;
        opacity: 1;
        overflow: hidden;
    }
    #cuerpoAccesos.collapsed {
        max-height: 0;
        opacity: 0;
        padding: 0 16px !important;
    }
    .toggle-arrow {
        font-size: 14px;
        margin-left: auto;
        transition: transform .3s;
        opacity: .8;
    }
    .toggle-arrow.rotated { transform: rotate(180deg); }

    .info-card {
        background: #fff;
        border-radius: 14px;
        padding: 28px 22px;
        text-align: center;
        box-shadow: 0 2px 16px rgba(0,0,0,.06);
        transition: transform .2s;
        height: 100%;
    }
    .info-card:hover { transform: translateY(-3px); }
    .info-card .ic-icon {
        width: 60px; height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 24px;
        color: #fff;
    }
    .info-card h4 { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .info-card p  { font-size: 13px; color: #94a3b8; margin: 0; }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(20px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .anim-1 { animation: fadeUp .5s ease both; }
    .anim-2 { animation: fadeUp .5s .1s ease both; }
    .anim-3 { animation: fadeUp .5s .2s ease both; }
    .anim-4 { animation: fadeUp .5s .3s ease both; }

    /* Badge de rol en el hero */
    .rol-badge {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
        vertical-align: middle;
    }
    .rol-badge.admin  { background: rgba(251,191,36,.25); border: 1px solid rgba(251,191,36,.4); }
    .rol-badge.usuario { background: rgba(167,243,208,.25); border: 1px solid rgba(167,243,208,.4); }
</style>

<section class="content dash-wrap">

    <!-- HERO -->
    <div class="dash-hero anim-1">
        <span class="badge-date">
            <i class="fa fa-calendar"></i>
            <?= date('d \d\e F \d\e Y') ?>
        </span>
        <h1>
            <i class="fa fa-address-book" style="margin-right:10px;"></i>
            Sistema de Agenda 2026
            <?php if ($rol === "Administrador"): ?>
                <span class="rol-badge admin"><i class="fa fa-shield"></i> Admin</span>
            <?php else: ?>
                <span class="rol-badge usuario"><i class="fa fa-user"></i> Usuario</span>
            <?php endif; ?>
        </h1>
        <p>
            <?php if ($rol === "Administrador"): ?>
                Administra operadores, empresas, grupos y contactos de manera rápida y segura.
            <?php else: ?>
                Bienvenido/a, <?= htmlspecialchars($_SESSION["nombre"]) ?>. Aquí puedes consultar tu agenda de contactos.
            <?php endif; ?>
        </p>
        <i class="fa fa-address-book hero-icon"></i>
    </div>

    <!-- TARJETAS ESTADÍSTICAS -->
    <div class="row anim-2">

        <?php if ($rol === "Administrador"): ?>
        <!-- Admin: ve todos los módulos con links -->
        <div class="col-lg-3 col-sm-6">
            <a href="index.php?Pages=Listar_Operador" class="stat-card sc-blue">
                <div class="sc-num counter" data-target="<?= $total_operadores ?>">0</div>
                <div class="sc-label">Operadores</div>
                <div class="sc-sub">Registrados en el sistema</div>
                <i class="fa fa-user-plus sc-bg-icon"></i>
                <i class="fa fa-angle-right sc-arrow"></i>
            </a>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="index.php?Pages=Listar_Empresa" class="stat-card sc-green">
                <div class="sc-num counter" data-target="<?= $total_empresas ?>">0</div>
                <div class="sc-label">Empresas</div>
                <div class="sc-sub">Asociadas al sistema</div>
                <i class="fa fa-building sc-bg-icon"></i>
                <i class="fa fa-angle-right sc-arrow"></i>
            </a>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="index.php?Pages=Listar_Grupo" class="stat-card sc-amber">
                <div class="sc-num counter" data-target="<?= $total_grupos ?>">0</div>
                <div class="sc-label">Grupos</div>
                <div class="sc-sub">Categorías de contacto</div>
                <i class="fa fa-users sc-bg-icon"></i>
                <i class="fa fa-angle-right sc-arrow"></i>
            </a>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="index.php?Pages=Listar_Contacto" class="stat-card sc-rose">
                <div class="sc-num counter" data-target="<?= $total_contactos ?>">0</div>
                <div class="sc-label">Contactos</div>
                <div class="sc-sub">En tu agenda</div>
                <i class="fa fa-address-book sc-bg-icon"></i>
                <i class="fa fa-angle-right sc-arrow"></i>
            </a>
        </div>

        <?php else: ?>
        <!-- Usuario: ve solo las tarjetas informativas sin links de admin -->
        <div class="col-lg-3 col-sm-6">
            <div class="stat-card sc-blue" style="cursor:default;">
                <div class="sc-num counter" data-target="<?= $total_operadores ?>">0</div>
                <div class="sc-label">Operadores</div>
                <div class="sc-sub">Registrados en el sistema</div>
                <i class="fa fa-user-plus sc-bg-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="stat-card sc-green" style="cursor:default;">
                <div class="sc-num counter" data-target="<?= $total_empresas ?>">0</div>
                <div class="sc-label">Empresas</div>
                <div class="sc-sub">Asociadas al sistema</div>
                <i class="fa fa-building sc-bg-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="stat-card sc-amber" style="cursor:default;">
                <div class="sc-num counter" data-target="<?= $total_grupos ?>">0</div>
                <div class="sc-label">Grupos</div>
                <div class="sc-sub">Categorías de contacto</div>
                <i class="fa fa-users sc-bg-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <a href="index.php?Pages=Listar_Contacto" class="stat-card sc-rose">
                <div class="sc-num counter" data-target="<?= $total_contactos ?>">0</div>
                <div class="sc-label">Contactos</div>
                <div class="sc-sub">En tu agenda</div>
                <i class="fa fa-address-book sc-bg-icon"></i>
                <i class="fa fa-angle-right sc-arrow"></i>
            </a>
        </div>
        <?php endif; ?>

    </div>

    <!-- ÚLTIMOS CONTACTOS + ACCESOS RÁPIDOS / PERFIL -->
    <div class="row anim-3">

        <div class="col-md-8">
            <div class="recent-box">
                <div class="rb-header">
                    <i class="fa fa-clock-o"></i>
                    <h4>Últimos Contactos Registrados</h4>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Contacto</th>
                            <th>Empresa</th>
                            <th>Grupo</th>
                            <th>Teléfono</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $colores = ['#1d4ed8','#059669','#d97706','#be123c','#7c3aed'];
                        foreach ($ultimos as $i => $u):
                            $iniciales = strtoupper(substr($u['nombres'],0,1) . substr($u['apellidos'],0,1));
                            $color     = $colores[$i % count($colores)];
                            $grupo_colores = [
                                'Familia'     => '#10b981',
                                'Amigos'      => '#3b82f6',
                                'Trabajo'     => '#f59e0b',
                                'Clientes'    => '#ef4444',
                                'Proveedores' => '#8b5cf6',
                            ];
                            $gc = $grupo_colores[$u['nombre_grupo']] ?? '#64748b';
                        ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="avatar-circle" style="background:<?= $color ?>">
                                            <?= $iniciales ?>
                                        </div>
                                        <strong style="color:#1e293b;font-size:13px;">
                                            <?= htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']) ?>
                                        </strong>
                                    </div>
                                </td>
                                <td style="color:#475569;">
                                    <i class="fa fa-building-o" style="color:#94a3b8;margin-right:5px;"></i>
                                    <?= htmlspecialchars($u['nombre_empresa']) ?>
                                </td>
                                <td>
                                    <span class="badge-grupo"
                                        style="background:<?= $gc ?>20;color:<?= $gc ?>;border:1px solid <?= $gc ?>40;">
                                        <?= htmlspecialchars($u['nombre_grupo']) ?>
                                    </span>
                                </td>
                                <td style="color:#475569;font-size:13px;">
                                    <i class="fa fa-mobile" style="color:#94a3b8;margin-right:4px;"></i>
                                    <?= htmlspecialchars($u['telefono_movil']) ?>
                                </td>
                                <td style="color:#94a3b8;font-size:12px;">
                                    <?= date('d/m/Y', strtotime($u['fecha_registro'])) ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div style="padding:12px 22px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:right;">
                    <a href="index.php?Pages=Listar_Contacto" class="btn btn-sm btn-primary">
                        Ver todos <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Admin ve Accesos Rápidos, Usuario ve su perfil -->
        <div class="col-md-4">

            <?php if ($rol === "Administrador"): ?>

            <!-- ACCESOS RÁPIDOS (solo admin) -->
            <div class="recent-box" style="margin-bottom:20px;">
                <div class="rb-header"
                    style="cursor:pointer;user-select:none;"
                    onclick="toggleAccesos()">
                    <i class="fa fa-pencil-square-o"></i>
                    <h4 style="flex:1;">Accesos Rápidos</h4>
                    <i class="fa fa-angle-up toggle-arrow" id="iconoAccesos"></i>
                </div>
                <div id="cuerpoAccesos" style="padding:14px 16px;">
                    <a href="index.php?Pages=Contacto" class="acceso-btn">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#be123c,#f43f5e);">
                            <i class="fa fa-address-book"></i>
                        </span>
                        <span class="ab-text">Nuevo Contacto</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                    <a href="index.php?Pages=Empresa" class="acceso-btn">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#059669,#10b981);">
                            <i class="fa fa-building"></i>
                        </span>
                        <span class="ab-text">Nueva Empresa</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                    <a href="index.php?Pages=Operador" class="acceso-btn">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);">
                            <i class="fa fa-user-plus"></i>
                        </span>
                        <span class="ab-text">Nuevo Operador</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                    <a href="index.php?Pages=Grupo" class="acceso-btn">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#d97706,#fbbf24);">
                            <i class="fa fa-users"></i>
                        </span>
                        <span class="ab-text">Nuevo Grupo</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                    <a href="index.php?Pages=Listar_Contacto" class="acceso-btn" style="margin-bottom:0;">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                            <i class="fa fa-list"></i>
                        </span>
                        <span class="ab-text">Ver Agenda Completa</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                </div>
            </div>

            <?php else: ?>

            <!-- MINI PERFIL (solo usuario normal) -->
            <div class="recent-box" style="margin-bottom:20px;">
                <div class="rb-header">
                    <i class="fa fa-user-circle"></i>
                    <h4>Mi Cuenta</h4>
                </div>
                <div style="padding:24px 20px;text-align:center;">
                    <img src="<?= $_SESSION['foto'] ?? 'Views/Images/Users/default.png' ?>"
                         class="img-circle"
                         style="width:80px;height:80px;object-fit:cover;border:3px solid #e2e8f0;margin-bottom:14px;">
                    <h4 style="margin:0 0 4px;font-size:16px;font-weight:700;color:#1e293b;">
                        <?= htmlspecialchars($_SESSION["nombre"]) ?>
                    </h4>
                    <p style="color:#64748b;font-size:13px;margin:0 0 20px;">
                        <i class="fa fa-circle text-success" style="font-size:9px;"></i>
                        <?= htmlspecialchars($_SESSION["rol"]) ?>
                    </p>
                    <a href="index.php?Pages=Listar_Contacto" class="acceso-btn" style="margin-bottom:10px;">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#be123c,#f43f5e);">
                            <i class="fa fa-address-book"></i>
                        </span>
                        <span class="ab-text">Mis Contactos</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                    <a href="index.php?Pages=Perfil" class="acceso-btn" style="margin-bottom:0;">
                        <span class="ab-icon" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);">
                            <i class="fa fa-user"></i>
                        </span>
                        <span class="ab-text">Ver Mi Perfil</span>
                        <i class="fa fa-angle-right ab-arrow"></i>
                    </a>
                </div>
            </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- INFO CARDS BOTTOM -->
    <div class="row anim-4">

        <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);">
                    <i class="fa fa-user-plus"></i>
                </div>
                <h4>Operadores</h4>
                <p>Registra y administra operadores telefónicos del sistema.</p>
            </div>
        </div>

        <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#059669,#10b981);">
                    <i class="fa fa-building"></i>
                </div>
                <h4>Empresas</h4>
                <p>Controla todas las empresas asociadas a tus contactos.</p>
            </div>
        </div>

        <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#d97706,#fbbf24);">
                    <i class="fa fa-users"></i>
                </div>
                <h4>Grupos</h4>
                <p>Organiza tus contactos por familia, trabajo, clientes y más.</p>
            </div>
        </div>

        <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#be123c,#f43f5e);">
                    <i class="fa fa-address-book"></i>
                </div>
                <h4>Contactos</h4>
                <p>Gestiona toda tu agenda con información detallada.</p>
            </div>
        </div>

    </div>

</section>

<script>
function toggleAccesos() {
    var cuerpo = document.getElementById('cuerpoAccesos');
    var icono  = document.getElementById('iconoAccesos');
    cuerpo.classList.toggle('collapsed');
    icono.classList.toggle('rotated');
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.counter').forEach(function (el) {
        var target   = parseInt(el.getAttribute('data-target'));
        var duration = 1200;
        var step     = Math.ceil(target / (duration / 30));
        var current  = 0;
        var timer    = setInterval(function () {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = current;
        }, 30);
    });
});
</script>
