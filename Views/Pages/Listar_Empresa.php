<?php
require_once "Models/Conexion.php";

$pdo = Conexion::conectar();
$msg = '';

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id             = trim($_POST['id_empresa']);
    $nombre_empresa = trim($_POST['nombre_empresa']);
    $direccion      = trim($_POST['direccion']);
    $telefono       = trim($_POST['telefono']);

    $stmt = $pdo->prepare("UPDATE empresa SET nombre_empresa=?, direccion=?, telefono=? WHERE id_empresa=?");
    $stmt->execute([$nombre_empresa, $direccion, $telefono, $id]);
    $msg = 'La empresa fue actualizada correctamente.';
}

// ELIMINAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id   = trim($_POST['id_empresa']);
    $stmt = $pdo->prepare("DELETE FROM empresa WHERE id_empresa=?");
    $stmt->execute([$id]);
    $msg = 'La empresa fue eliminada correctamente.';
}

$empresas = $pdo->query("SELECT * FROM empresa ORDER BY nombre_empresa")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="content">
    <div class="box">

        <div class="box-header with-border">
            <a href="index.php?page=Empresa" class="btn btn-success">
                <i class="fa fa-plus"></i> Nueva Empresa
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
                        <th>Empresa</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresas as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['id_empresa']) ?></td>
                            <td><?= htmlspecialchars($e['nombre_empresa']) ?></td>
                            <td><?= htmlspecialchars($e['direccion']) ?></td>
                            <td><?= htmlspecialchars($e['telefono']) ?></td>
                            <td>
                                <!-- Botón Editar -->
                                <button class="btn btn-warning btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEditar"

                                    data-id="<?= $e['id_empresa'] ?>"

                                    data-nombre="<?= htmlspecialchars($e['nombre_empresa']) ?>"

                                    data-direccion="<?= htmlspecialchars($e['direccion'] ?? '') ?>"

                                    data-telefono="<?= htmlspecialchars($e['telefono'] ?? '') ?>">

                                    <i class="fa fa-pencil"></i>

                                </button>
                                <!-- Botón Eliminar -->
                                <button class="btn btn-danger btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEliminar"
                                    data-id="<?= $e['id_empresa'] ?>"
                                    data-nombre="<?= htmlspecialchars($e['nombre_empresa']) ?>">
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
                <input type="hidden" name="id_empresa" id="edit_id">

                <div class="modal-header" style="background:#f39c12; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Empresa</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label><i class="fa fa-briefcase"></i> Nombre Empresa</label>
                        <input type="text" class="form-control" name="nombre_empresa" id="edit_nombre" maxlength="60" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-map-marker"></i> Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="edit_direccion" maxlength="80" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-phone"></i> Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="edit_telefono" maxlength="11" required>
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
                <input type="hidden" name="id_empresa" id="del_id">

                <div class="modal-header" style="background:#dd4b39; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Eliminar Empresa</h4>
                </div>

                <div class="modal-body text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                    <p class="margin-top">¿Está seguro de eliminar la empresa<br><strong id="del_nombre"></strong>?</p>
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
// Llenar modal Editar
$('#modalEditar').on('show.bs.modal', function(e) {
    var btn = $(e.relatedTarget);
    $('#edit_id').val(btn.data('id'));
    $('#edit_nombre').val(btn.data('nombre'));
    $('#edit_direccion').val(btn.data('direccion'));
    $('#edit_telefono').val(btn.data('telefono'));
});

// Llenar modal Eliminar
$('#modalEliminar').on('show.bs.modal', function(e) {
    var btn = $(e.relatedTarget);
    $('#del_id').val(btn.data('id'));
    $('#del_nombre').text(btn.data('nombre'));
});
</script>