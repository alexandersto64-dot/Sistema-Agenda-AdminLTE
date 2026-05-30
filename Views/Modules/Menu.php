<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/* =========================
   VALIDACIÓN SEGURA
========================= */

if(!isset($_SESSION["usuario"])){
    header("Location: index.php");
    exit;
}

/* =========================
   DATOS SESIÓN
========================= */

$nombre = $_SESSION["nombre"] ?? "Usuario";
$foto   = $_SESSION["foto"] ?? "Views/Images/Users/default.jpg";

/* evita caché de imagen */
$foto .= "?v=" . time();

$rol = $_SESSION["rol"] ?? "Usuario";

?>

<aside class="main-sidebar">

  <section class="sidebar">

    <!-- =========================
         USUARIO PANEL
    ========================== -->

    <div class="user-panel">

      <div class="pull-left image">

        <img src="<?= $foto ?>"
             class="img-circle"
             alt="Usuario">

      </div>

      <div class="pull-left info">

        <p><?= htmlspecialchars($nombre) ?></p>

        <a href="#">
            <i class="fa fa-circle text-success"></i>
            <?= htmlspecialchars($rol) ?>
        </a>

      </div>

    </div>

    <!-- =========================
         MENÚ
    ========================== -->

    <ul class="sidebar-menu" data-widget="tree">

      <li class="header">MENÚ DE NAVEGACIÓN</li>

      <!-- ================= ADMIN ================= -->

      <?php if($rol === "Administrador"){ ?>

      <li class="treeview">

        <a href="#">
          <i class="fa fa-pencil-square-o text-red"></i>
          <span>Registros</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">

<li><a href="index.php?Pages=Operador">
  <i class="fa fa-user-plus text-red"></i> Operador
</a></li>

<li><a href="index.php?Pages=Empresa">
  <i class="fa fa-building text-blue"></i> Empresa
</a></li>

<li><a href="index.php?Pages=Contacto">
  <i class="fa fa-address-book text-green"></i> Contacto
</a></li>

<li><a href="index.php?Pages=Grupo">
  <i class="fa fa-users text-yellow"></i> Grupo
</a></li>

        </ul>

      </li>

      <li class="treeview">

        <a href="#">
          <i class="fa fa-file-text-o text-green"></i>
          <span>Reportes</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">

<li>
  <a href="index.php?Pages=Listar_Operador">
    <i class="fa fa-list text-red"></i> Listar Operador
  </a>
</li>

<li>
  <a href="index.php?Pages=Listar_Empresa">
    <i class="fa fa-building-o text-blue"></i> Listar Empresa
  </a>
</li>

<li>
  <a href="index.php?Pages=Listar_Contacto">
    <i class="fa fa-address-card text-green"></i> Listar Contacto
  </a>
</li>

<li>
  <a href="index.php?Pages=Listar_Grupo">
    <i class="fa fa-object-group text-yellow"></i> Listar Grupo
  </a>
</li>

        </ul>

      </li>

      <?php } ?>

      <!-- ================= USUARIO NORMAL ================= -->

      <?php if($rol === "Usuario"){ ?>

      <li>

        <a href="index.php?Pages=Listar_Contacto">
          <i class="fa fa-address-book text-green"></i>
          <span>Mis Contactos</span>
        </a>

      </li>

      <li>

        <a href="index.php?Pages=Perfil">
          <i class="fa fa-user text-aqua"></i>
          <span>Mi Perfil</span>
        </a>

      </li>

      <?php } ?>

    </ul>

  </section>

</aside>