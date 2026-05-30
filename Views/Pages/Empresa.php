<?php
require_once __DIR__ . '/../../Models/Conexion.php';

$errors = [];
$success = false;
$nombre_empresa = $direccion = $telefono = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre_empresa = trim($_POST['nombre_empresa'] ?? '');
    $direccion      = trim($_POST['direccion'] ?? '');
    $telefono       = trim($_POST['telefono'] ?? '');

    if ($nombre_empresa === '') {
        $errors['nombre_empresa'] = 'Por favor ingrese el nombre de la empresa.';
    } elseif (strlen($nombre_empresa) > 60) {
        $errors['nombre_empresa'] = 'El nombre no puede tener más de 60 caracteres.';
    }

    if ($direccion === '') {
        $errors['direccion'] = 'Por favor ingrese la dirección.';
    } elseif (strlen($direccion) > 80) {
        $errors['direccion'] = 'La dirección no puede tener más de 80 caracteres.';
    }

    if ($telefono === '') {
        $errors['telefono'] = 'Por favor ingrese el teléfono.';
    } elseif (!preg_match('/^\d{7,11}$/', $telefono)) {
        $errors['telefono'] = 'El teléfono debe contener entre 7 y 11 dígitos.';
    }

    if (empty($errors)) {
        $pdo  = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO empresa (nombre_empresa, direccion, telefono) VALUES (?, ?, ?)");
        $stmt->execute([$nombre_empresa, $direccion, $telefono]);
        $success = true;
        $nombre_empresa = $direccion = $telefono = '';
    }
}
?>

<section class="content">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="box box-success">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-building"></i>
                        Nueva Empresa
                    </h3>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible margin" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <i class="fa fa-check-circle"></i>
                        <strong>¡Éxito!</strong> La empresa fue registrada correctamente.
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="box-body">

                        <div class="form-group <?= isset($errors['nombre_empresa']) ? 'has-error' : '' ?>">
                            <label><i class="fa fa-briefcase"></i> Nombre Empresa</label>
                            <input type="text"
                                class="form-control input-lg"
                                name="nombre_empresa"
                                placeholder="Ingrese nombre de la empresa"
                                maxlength="60"
                                value="<?= htmlspecialchars($nombre_empresa) ?>">
                            <?php if (isset($errors['nombre_empresa'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['nombre_empresa'] ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($errors['direccion']) ? 'has-error' : '' ?>">
                            <label><i class="fa fa-map-marker"></i> Dirección</label>
                            <input type="text"
                                class="form-control input-lg"
                                name="direccion"
                                placeholder="Ingrese dirección"
                                maxlength="80"
                                value="<?= htmlspecialchars($direccion) ?>">
                            <?php if (isset($errors['direccion'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['direccion'] ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($errors['telefono']) ? 'has-error' : '' ?>">
                            <label><i class="fa fa-phone"></i> Teléfono</label>
                            <input type="text"
                                class="form-control input-lg"
                                name="telefono"
                                placeholder="Ingrese teléfono"
                                maxlength="11"
                                value="<?= htmlspecialchars($telefono) ?>">
                            <?php if (isset($errors['telefono'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['telefono'] ?>
                                </span>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="box-footer text-right">
                        <a href="index.php?Pages=Listar_Empresa" class="btn btn-default btn-lg">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</section>