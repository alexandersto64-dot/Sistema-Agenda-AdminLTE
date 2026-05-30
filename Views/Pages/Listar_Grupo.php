<?php
require_once "Models/Conexion.php";

$pdo = Conexion::conectar();
$msg = '';

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id          = trim($_POST['id_grupo_original']);
    $nombre_grupo = trim($_POST['nombre_grupo']);

    $stmt = $pdo->prepare("UPDATE grupo_contacto SET nombre_grupo=? WHERE id_grupo=?");
    $stmt->execute([$nombre_grupo, $id]);
    $msg = 'El grupo fue actualizado correctamente.';
}

// ELIMINAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id   = trim($_POST['id_grupo']);
    $stmt = $pdo->prepare("DELETE FROM grupo_contacto WHERE id_grupo=?");
    $stmt->execute([$id]);
    $msg = 'El grupo fue eliminado correctamente.';
}

$grupos = $pdo->query("SELECT * FROM grupo_contacto ORDER BY nombre_grupo")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="content">
    <div class="box">

        <div class="box-header with-border">
            <a href="index.php?page=Nuevo_Grupo" class="btn btn-primary">
                <i class="fa fa-plus"></i> Nuevo Grupo
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
                        <th>Grupo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grupos as $g): ?>
                        <tr>
                            <td><?= htmlspecialchars($g['id_grupo']) ?></td>
                            <td><?= htmlspecialchars($g['nombre_grupo']) ?></td>
                            <td><?= htmlspecialchars($g['fecha_registro']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEditar"
                                    data-id="<?= htmlspecialchars($g['id_grupo']) ?>"
                                    data-nombre="<?= htmlspecialchars($g['nombre_grupo']) ?>">
                                    <i class="fa fa-pencil"></i>
                                </button>   
                                <button class="btn btn-danger btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEliminar"
                                    data-id="<?= $g['id_grupo'] ?>"
                                    data-nombre="<?= htmlspecialchars($g['nombre_grupo']) ?>">
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
                <input type="hidden" name="id_grupo_original" id="edit_id">

                <div class="modal-header" style="background:#f39c12; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Grupo</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label><i class="fa fa-key"></i> ID Grupo</label>
                        <input type="text" class="form-control" id="edit_id_show" disabled>
                        <small class="text-muted">El ID no se puede modificar.</small>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-tag"></i> Nombre Grupo</label>
                        <input type="text" class="form-control" name="nombre_grupo" id="edit_nombre" maxlength="60" required>
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
                <input type="hidden" name="id_grupo" id="del_id">

                <div class="modal-header" style="background:#dd4b39; color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Eliminar Grupo</h4>
                </div>

                <div class="modal-body text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                    <p class="margin-top">¿Está seguro de eliminar el grupo<br><strong id="del_nombre"></strong>?</p>
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