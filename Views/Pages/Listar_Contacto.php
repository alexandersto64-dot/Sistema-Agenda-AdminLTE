<?php
require_once "Models/Conexion.php";

$pdo = Conexion::conectar();
$msg = '';
$es_admin = (($_SESSION['rol'] ?? '') === 'Administrador');

/* ================================================
   EDITAR Y ELIMINAR: solo si es Administrador.
   Si un Usuario envía POST igual se bloquea aquí.
   ================================================ */
if ($es_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    if ($_POST['accion'] === 'editar') {
        $id             = (int) $_POST['id_contacto'];
        $nombres        = trim($_POST['nombres']);
        $apellidos      = trim($_POST['apellidos']);
        $telefono_movil = trim($_POST['telefono_movil']);
        $correo         = trim($_POST['correo']);
        $id_empresa     = (int) $_POST['id_empresa'];
        $id_operador    = trim($_POST['id_operador']);
        $id_grupo       = trim($_POST['id_grupo']);

        $stmt = $pdo->prepare("
            UPDATE contacto
            SET nombres=?, apellidos=?, telefono_movil=?, correo=?,
                id_empresa=?, id_operador=?, id_grupo=?
            WHERE id_contacto=?
        ");
        $stmt->execute([
            $nombres, $apellidos, $telefono_movil,
            $correo ?: null, $id_empresa, $id_operador, $id_grupo, $id
        ]);
        $msg = 'El contacto fue actualizado correctamente.';
    }

    if ($_POST['accion'] === 'eliminar') {
        $id   = (int) $_POST['id_contacto'];
        $stmt = $pdo->prepare("DELETE FROM contacto WHERE id_contacto=?");
        $stmt->execute([$id]);
        $msg = 'El contacto fue eliminado correctamente.';
    }
}

$contactos = $pdo->query("
    SELECT
        c.id_contacto,
        c.nombres,
        c.apellidos,
        c.id_empresa,
        c.id_operador,
        c.id_grupo,
        c.telefono_movil,
        c.correo,
        e.nombre_empresa,
        o.nombre_operador,
        g.nombre_grupo
    FROM contacto c
    JOIN empresa        e ON c.id_empresa  = e.id_empresa
    JOIN operador       o ON c.id_operador = o.id_operador
    JOIN grupo_contacto g ON c.id_grupo    = g.id_grupo
    ORDER BY c.nombres
")->fetchAll(PDO::FETCH_ASSOC);

/* Solo carga los selects si es admin (para los modales de edición) */
if ($es_admin) {
    $empresas   = $pdo->query("SELECT id_empresa, nombre_empresa FROM empresa ORDER BY nombre_empresa")->fetchAll(PDO::FETCH_ASSOC);
    $operadores = $pdo->query("SELECT id_operador, nombre_operador FROM operador ORDER BY nombre_operador")->fetchAll(PDO::FETCH_ASSOC);
    $grupos     = $pdo->query("SELECT id_grupo, nombre_grupo FROM grupo_contacto ORDER BY nombre_grupo")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="content">
    <div class="box">

        <div class="box-header with-border">
            <?php if ($es_admin): ?>
                <a href="index.php?Pages=Contacto" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Nuevo Contacto
                </a>
            <?php else: ?>
                <span class="text-muted" style="font-size:13px;">
                    <i class="fa fa-eye"></i>
                    Estás viendo la agenda en modo <strong>solo lectura</strong>.
                </span>
            <?php endif; ?>
        </div>

        <div class="box-body">

            <?php if ($msg): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa fa-check-circle"></i> <strong>¡Éxito!</strong> <?= $msg ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-striped tablaData">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Empresa</th>
                        <th>Operador</th>
                        <th>Grupo</th>
                        <th>Celular</th>
                        <th>Correo</th>
                        <?php if ($es_admin): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['id_contacto']) ?></td>
                            <td><?= htmlspecialchars($c['nombres']) ?></td>
                            <td><?= htmlspecialchars($c['apellidos']) ?></td>
                            <td><?= htmlspecialchars($c['nombre_empresa']) ?></td>
                            <td><?= htmlspecialchars($c['nombre_operador']) ?></td>
                            <td><?= htmlspecialchars($c['nombre_grupo']) ?></td>
                            <td><?= htmlspecialchars($c['telefono_movil']) ?></td>
                            <td><?= htmlspecialchars($c['correo'] ?? '') ?></td>
                            <?php if ($es_admin): ?>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditar"
                                        data-id="<?= $c['id_contacto'] ?>"
                                        data-nombres="<?= htmlspecialchars($c['nombres']) ?>"
                                        data-apellidos="<?= htmlspecialchars($c['apellidos']) ?>"
                                        data-telefono="<?= htmlspecialchars($c['telefono_movil']) ?>"
                                        data-correo="<?= htmlspecialchars($c['correo'] ?? '') ?>"
                                        data-empresa="<?= $c['id_empresa'] ?>"
                                        data-operador="<?= htmlspecialchars($c['id_operador']) ?>"
                                        data-grupo="<?= htmlspecialchars($c['id_grupo']) ?>">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEliminar"
                                        data-id="<?= $c['id_contacto'] ?>"
                                        data-nombre="<?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>
</section>

<?php if ($es_admin): ?>

<!-- MODAL EDITAR -->
<div id="modalEditar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id_contacto" id="edit_id">

                <div class="modal-header" style="background:#f39c12; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Contacto</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-user"></i> Nombres</label>
                                <input type="text" class="form-control" name="nombres"
                                    id="edit_nombres" maxlength="80" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-user"></i> Apellidos</label>
                                <input type="text" class="form-control" name="apellidos"
                                    id="edit_apellidos" maxlength="80" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fa fa-building"></i> Empresa</label>
                                <select class="form-control" name="id_empresa" id="edit_empresa">
                                    <?php foreach ($empresas as $e): ?>
                                        <option value="<?= $e['id_empresa'] ?>">
                                            <?= htmlspecialchars($e['nombre_empresa']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fa fa-phone-square"></i> Operador</label>
                                <select class="form-control" name="id_operador" id="edit_operador">
                                    <?php foreach ($operadores as $o): ?>
                                        <option value="<?= $o['id_operador'] ?>">
                                            <?= htmlspecialchars($o['nombre_operador']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fa fa-users"></i> Grupo</label>
                                <select class="form-control" name="id_grupo" id="edit_grupo">
                                    <?php foreach ($grupos as $g): ?>
                                        <option value="<?= $g['id_grupo'] ?>">
                                            <?= htmlspecialchars($g['nombre_grupo']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-mobile"></i> Teléfono Móvil</label>
                                <input type="text" class="form-control" name="telefono_movil"
                                    id="edit_telefono" maxlength="11" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-envelope"></i> Correo
                                    <small class="text-muted">(opcional)</small>
                                </label>
                                <input type="email" class="form-control" name="correo"
                                    id="edit_correo" maxlength="90">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR -->
<div id="modalEliminar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id_contacto" id="del_id">

                <div class="modal-header" style="background:#dd4b39; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Eliminar Contacto</h4>
                </div>

                <div class="modal-body text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                    <p class="margin-top">¿Está seguro de eliminar a<br>
                        <strong id="del_nombre"></strong>?
                    </p>
                    <p class="text-muted"><small>Esta acción no se puede deshacer.</small></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Sí, eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).on('click', '[data-target="#modalEditar"]', function () {
    $('#edit_id').val($(this).attr('data-id'));
    $('#edit_nombres').val($(this).attr('data-nombres'));
    $('#edit_apellidos').val($(this).attr('data-apellidos'));
    $('#edit_telefono').val($(this).attr('data-telefono'));
    $('#edit_correo').val($(this).attr('data-correo'));
    $('#edit_empresa').val($(this).attr('data-empresa'));
    $('#edit_operador').val($(this).attr('data-operador'));
    $('#edit_grupo').val($(this).attr('data-grupo'));
});

$(document).on('click', '[data-target="#modalEliminar"]', function () {
    $('#del_id').val($(this).attr('data-id'));
    $('#del_nombre').text($(this).attr('data-nombre'));
});
</script>

<?php endif; ?>
