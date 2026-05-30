<?php
require_once "Models/Conexion.php";

$pdo = Conexion::conectar();
$msg = '';

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id              = trim($_POST['id_operador_original']);
    $nombre_operador = trim($_POST['nombre_operador']);

    $stmt = $pdo->prepare("UPDATE operador SET nombre_operador=? WHERE id_operador=?");
    $stmt->execute([$nombre_operador, $id]);
    $msg = 'El operador fue actualizado correctamente.';
}

// ELIMINAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id   = trim($_POST['id_operador']);
    $stmt = $pdo->prepare("DELETE FROM operador WHERE id_operador=?");
    $stmt->execute([$id]);
    $msg = 'El operador fue eliminado correctamente.';
}

$operadores = $pdo->query("SELECT * FROM operador ORDER BY nombre_operador")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="content">
    <div class="box">

        <div class="box-header with-border">
            <a href="index.php?page=Nuevo_Operador" class="btn btn-primary">
                <i class="fa fa-plus"></i> Nuevo Operador
            </a>
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
                        <th>Operador</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operadores as $o): ?>
                        <tr>
                            <td><?= htmlspecialchars($o['id_operador']) ?></td>
                            <td><?= htmlspecialchars($o['nombre_operador']) ?></td>
                            <td><?= htmlspecialchars($o['fecha_registro']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEditar"
                                    data-id="<?= $o['id_operador'] ?>"
                                    data-nombre="<?= htmlspecialchars($o['nombre_operador']) ?>">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEliminar"
                                    data-id="<?= $o['id_operador'] ?>"
                                    data-nombre="<?= htmlspecialchars($o['nombre_operador']) ?>">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>
</section>

<!-- MODAL EDITAR -->
<div id="modalEditar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id_operador_original" id="edit_id">

                <div class="modal-header" style="background:#f39c12; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Operador</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label><i class="fa fa-key"></i> ID Operador</label>
                        <input type="text" class="form-control" id="edit_id_show" disabled>
                        <small class="text-muted">El ID no se puede modificar.</small>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-phone-square"></i> Nombre Operador</label>
                        <input type="text" class="form-control" name="nombre_operador" id="edit_nombre" maxlength="60" required>
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
                <input type="hidden" name="id_operador" id="del_id">

                <div class="modal-header" style="background:#dd4b39; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Eliminar Operador</h4>
                </div>

                <div class="modal-body text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                    <p class="margin-top">¿Está seguro de eliminar el operador<br><strong id="del_nombre"></strong>?</p>
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
$('#modalEditar').on('show.bs.modal', function(e) {
    var btn = $(e.relatedTarget);
    $('#edit_id').val(btn.data('id'));
    $('#edit_id_show').val(btn.data('id'));
    $('#edit_nombre').val(btn.data('nombre'));
});

$('#modalEliminar').on('show.bs.modal', function(e) {
    var btn = $(e.relatedTarget);
    $('#del_id').val(btn.data('id'));
    $('#del_nombre').text(btn.data('nombre'));
});
</script>