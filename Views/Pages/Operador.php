<?php
require_once __DIR__ . '/../../Models/Conexion.php';

$errors = [];
$success = false;
$id_operador = $nombre_operador = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_operador     = trim($_POST['id_operador'] ?? '');
    $nombre_operador = trim($_POST['nombre_operador'] ?? '');

    if ($id_operador === '') {
        $errors['id_operador'] = 'Por favor ingrese el ID del operador.';
    } elseif (strlen($id_operador) > 5) {
        $errors['id_operador'] = 'El ID no puede tener más de 5 caracteres.';
    }

    if ($nombre_operador === '') {
        $errors['nombre_operador'] = 'Por favor ingrese el nombre del operador.';
    }

    if (empty($errors)) {
        $pdo  = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO operador (id_operador, nombre_operador) VALUES (?, ?)");
        $stmt->execute([$id_operador, $nombre_operador]);
        $success = true;
        $id_operador = $nombre_operador = '';
    }
}
?>

<section class="content">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="box box-warning">

                <div class="box-header with-border bg-yellow">
                    <h3 class="box-title text-black">
                        <i class="fa fa-phone-square"></i>
                        Nuevo Operador
                    </h3>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible margin" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <i class="fa fa-check-circle"></i>
                        <strong>¡Éxito!</strong> El operador fue registrado correctamente.
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="box-body">

                        <div class="form-group <?= isset($errors['id_operador']) ? 'has-error' : '' ?>">
                            <label><i class="fa fa-key"></i> ID Operador</label>
                            <input type="text"
                                class="form-control input-lg"
                                name="id_operador"
                                placeholder="Ejemplo: OP001"
                                maxlength="5"
                                value="<?= htmlspecialchars($id_operador) ?>">
                            <?php if (isset($errors['id_operador'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['id_operador'] ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($errors['nombre_operador']) ? 'has-error' : '' ?>">
                            <label><i class="fa fa-phone"></i> Nombre Operador</label>
                            <input type="text"
                                class="form-control input-lg"
                                name="nombre_operador"
                                placeholder="Ingrese operador"
                                value="<?= htmlspecialchars($nombre_operador) ?>">
                            <?php if (isset($errors['nombre_operador'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['nombre_operador'] ?>
                                </span>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="box-footer text-right">
                        <a href="index.php?Pages=Listar_Operador" class="btn btn-default btn-lg">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</section>