
<?php

if(session_status() == PHP_SESSION_NONE){

    session_start();

}

?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Sistema Agenda 2026</title>

  <!-- Responsive -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap -->
  <link rel="stylesheet"
        href="Views/Resources/bower_components/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
        href="Views/Resources/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Font Awesome CDN -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet"
        href="Views/Resources/bower_components/Ionicons/css/ionicons.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet"
        href="Views/Resources/dist/css/AdminLTE.min.css">

  <!-- Skins -->
  <link rel="stylesheet"
        href="Views/Resources/dist/css/skins/_all-skins.min.css">

  <!-- DataTables -->
  <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap.min.css">

  <link rel="stylesheet"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css">

  <link rel="stylesheet"
        href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap.min.css">

  <!-- CSS PERSONALIZADO -->
  <link rel="stylesheet"
        href="Views/Resources/custom.css">

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">

  <style>

    .user-menu .dropdown-menu{

      right: 0;
      left: auto;

    }

  </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">

<?php

/* =========================================
   SI NO HAY SESION
========================================= */

if(!isset($_SESSION["usuario"])){

    include "Pages/Login.php";

}else{

?>

<div class="wrapper">

  <!-- HEADER -->
  <?php include "Modules/Header.php"; ?>

  <!-- MENU -->
  <?php include "Modules/Menu.php"; ?>

  <!-- CONTENIDO -->
  <div class="content-wrapper">

    <?php

    /* ================================================
       DEFINICIÓN DE PÁGINAS Y PERMISOS POR ROL
       - paginas_admin : solo Administrador
       - paginas_user  : cualquier usuario autenticado
       ================================================ */

    $paginas_admin = [
      "Operador",
      "Empresa",
      "Grupo",
      "Contacto",
      "Listar_Operador",
      "Listar_Empresa",
      "Listar_Grupo",
      "Listar_Contacto",
    ];

    $paginas_user = [
      "Inicio",
      "Perfil",
      "Acceso_Denegado",
    ];

    $paginas = array_merge($paginas_admin, $paginas_user);

    $rol_sesion = $_SESSION["rol"] ?? "Usuario";

    if(isset($_GET["Pages"])){

        $pagina = $_GET["Pages"];

        if(in_array($pagina, $paginas)){

            /* Verificar permiso: si la página es solo admin y el usuario no lo es */
            if(in_array($pagina, $paginas_admin) && $rol_sesion !== "Administrador"){
                include "Pages/Acceso_Denegado.php";
            } else {
                include "Pages/" . $pagina . ".php";
            }

        }else{

            echo '

            <section class="content">

              <div class="error-page">

                <h2 class="headline text-red">404</h2>

                <div class="error-content">

                  <h3>

                    <i class="fa fa-warning text-red"></i>

                    Página no encontrada

                  </h3>

                  <p>

                    La página que intenta acceder no existe.

                  </p>

                </div>

              </div>

            </section>

            ';

        }

    }else{

        include "Pages/Inicio.php";

    }

    ?>

  </div>

  <!-- FOOTER -->
  <?php include "Modules/Footer.php"; ?>

</div>

<?php } ?>
<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog">

  <div class="modal-dialog modal-sm">

    <div class="modal-content">

      <?php
      $foto = $_SESSION["foto"] ?? "Views/Images/Users/default.jpg";
      $nombre = $_SESSION["nombre"] ?? "Usuario";
      $usuario = $_SESSION["usuario"] ?? "";
      $rol = $_SESSION["rol"] ?? "";
      ?>

      <!-- HEADER -->
      <div class="modal-header bg-primary text-center">

        <button type="button" class="close" data-dismiss="modal" style="color:white;">
            &times;
        </button>

        <h4 class="modal-title">
            <i class="fa fa-user-circle"></i> Perfil de Usuario
        </h4>

      </div>

      <!-- BODY -->
      <div class="modal-body text-center">

        <img src="<?= $foto ?>"
             class="img-circle"
             style="width:90px; height:90px; object-fit:cover; border:3px solid #3c8dbc;">

        <h4 style="margin-top:10px;">
            <?= htmlspecialchars($nombre) ?>
        </h4>

        <p class="text-muted">
            <?= htmlspecialchars($rol) ?>
        </p>

        <hr>

        <p style="margin:0;">
            <strong>Usuario:</strong><br>
            <?= htmlspecialchars($usuario) ?>
        </p>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">

        <button class="btn btn-default btn-block" data-dismiss="modal">
            <i class="fa fa-times"></i> Cerrar
        </button>

      </div>

    </div>

  </div>

</div>

<!-- ===================== SCRIPTS ===================== -->

<!-- jQuery -->
<script src="Views/Resources/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="Views/Resources/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- SlimScroll -->
<script src="Views/Resources/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<!-- FastClick -->
<script src="Views/Resources/bower_components/fastclick/lib/fastclick.js"></script>

<!-- AdminLTE -->
<script src="Views/Resources/dist/js/adminlte.min.js"></script>

<!-- Demo -->
<script src="Views/Resources/dist/js/demo.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap.min.js"></script>

<!-- Responsive -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap.min.js"></script>

<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap.min.js"></script>

<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Print -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- ===================== CONFIGURACIONES ===================== -->


<script>

$(document).ready(function () {

    // =========================================
    // ACTIVAR SIDEBAR
    // =========================================

    $('.sidebar-menu').tree();

    // =========================================
    // DATATABLE
    // =========================================

    $('.tablaData').DataTable({

        responsive: true,

        autoWidth: false,

        pageLength: 5,

        lengthMenu: [

            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100]

        ],

        dom: 'Bfrtip',

        buttons: [

            {
                extend: 'excel',

                text:
                '<i class="fa fa-file-excel-o"></i> Excel',

                className: 'btn btn-success'
            },

            {
                extend: 'csv',

                text:
                '<i class="fa fa-file-text-o"></i> CSV',

                className: 'btn btn-info'
            },

            {
                extend: 'pdf',

                text:
                '<i class="fa fa-file-pdf-o"></i> PDF',

                className: 'btn btn-danger'
            },

            {
                extend: 'print',

                text:
                '<i class="fa fa-print"></i> Imprimir',

                className: 'btn btn-default'
            }

        ],

        language: {

            decimal: "",

            emptyTable:
            "No hay información",

            info:
            "Mostrando _START_ a _END_ de _TOTAL_ registros",

            infoEmpty:
            "Mostrando 0 a 0 de 0 registros",

            infoFiltered:
            "(Filtrado de _MAX_ registros totales)",

            thousands: ",",

            lengthMenu:
            "Mostrar _MENU_ registros",

            loadingRecords:
            "Cargando...",

            processing:
            "Procesando...",

            search:
            "Buscar:",

            zeroRecords:
            "No se encontraron resultados",

            paginate: {

                first: "Primero",

                last: "Último",

                next: "›",

                previous: "‹"

            },

            buttons: {

                copy: "Copiar",

                colvis: "Columnas"

            }

        }

    });

    // =========================================
    // MODAL EDITAR
    // =========================================

    $(document).on(

        'click',

        '[data-target="#modalEditar"]',

        function () {

            $('#edit_id').val(
                $(this).attr('data-id')
            );

            $('#edit_id_show').val(
                $(this).attr('data-id')
            );

            $('#edit_nombre').val(
                $(this).attr('data-nombre')
            );

            $('#edit_direccion').val(
                $(this).attr('data-direccion')
            );

            $('#edit_telefono').val(
                $(this).attr('data-telefono')
            );

            $('#edit_nombres').val(
                $(this).attr('data-nombres')
            );

            $('#edit_apellidos').val(
                $(this).attr('data-apellidos')
            );

            $('#edit_correo').val(
                $(this).attr('data-correo')
            );

            $('#edit_empresa').val(
                $(this).attr('data-empresa')
            );

            $('#edit_operador').val(
                $(this).attr('data-operador')
            );

            $('#edit_grupo').val(
                $(this).attr('data-grupo')
            );

        }

    );

    // =========================================
    // MODAL ELIMINAR
    // =========================================

    $(document).on(

        'click',

        '[data-target="#modalEliminar"]',

        function () {

            $('#del_id').val(
                $(this).attr('data-id')
            );

            $('#del_nombre').text(
                $(this).attr('data-nombre')
            );

        }

    );

});

</script>



</body>
</html>