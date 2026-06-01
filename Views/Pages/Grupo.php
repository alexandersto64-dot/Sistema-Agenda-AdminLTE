<?php
require_once __DIR__ . '/../../Models/Conexion.php';

$errors = [];
$success = false;
$id_grupo = $nombre_grupo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_grupo     = trim($_POST['id_grupo'] ?? '');
    $nombre_grupo = trim($_POST['nombre_grupo'] ?? '');

    if ($id_grupo === '') {
        $errors['id_grupo'] = 'Por favor ingrese el ID del grupo.';
    } elseif (strlen($id_grupo) > 5) {
        $errors['id_grupo'] = 'El ID no puede tener más de 5 caracteres.';
    }

    if ($nombre_grupo === '') {
        $errors['nombre_grupo'] = 'Por favor ingrese el nombre del grupo.';
    } elseif (strlen($nombre_grupo) > 60) {
        $errors['nombre_grupo'] = 'El nombre no puede tener más de 60 caracteres.';
    }

    if (empty($errors)) {
        $pdo  = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO grupo_contacto (id_grupo, nombre_grupo) VALUES (?, ?)");
        $stmt->execute([$id_grupo, $nombre_grupo]);
        $success = true;
        $id_grupo = $nombre_grupo = '';
    }
}
?>

<section class="content">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="box box-primary">

                <div class="box-header with-border bg-blue">
                    <h3 class="box-title text-white">
                        <i class="fa fa-users"></i>
                        Nuevo Grupo de Contacto
                    </h3>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible margin" role="alert">

                        <button type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-label="Cerrar">

                            <span aria-hidden="true">&times;</span>
                        </button>

                        <i class="fa fa-check-circle"></i>

                        <strong>¡Éxito!</strong>
                        El grupo de contacto fue registrado correctamente.
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="box-body">

                        <div class="form-group <?= isset($errors['id_grupo']) ? 'has-error' : '' ?>">

                            <label>
                                <i class="fa fa-key"></i>
                                ID Grupo
                            </label>

                            <input type="text"
                                class="form-control input-lg"
                                name="id_grupo"
                                placeholder="Ejemplo: GR001"
                                maxlength="5"
                                value="<?= htmlspecialchars($id_grupo) ?>">

                            <?php if (isset($errors['id_grupo'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['id_grupo'] ?>
                                </span>
                            <?php endif; ?>

                        </div>

                        <div class="form-group <?= isset($errors['nombre_grupo']) ? 'has-error' : '' ?>">

                            <label>
                                <i class="fa fa-tag"></i>
                                Nombre Grupo
                            </label>

                            <input type="text"
                                class="form-control input-lg"
                                name="nombre_grupo"
                                placeholder="Ingrese nombre del grupo"
                                maxlength="60"
                                value="<?= htmlspecialchars($nombre_grupo) ?>">

                            <?php if (isset($errors['nombre_grupo'])): ?>
                                <span class="help-block">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= $errors['nombre_grupo'] ?>
                                </span>
                            <?php endif; ?>

                        </div>

                    </div>

                    <div class="box-footer text-right">

                        <a href="index.php?Pages=Listar_Grupo"
                            class="btn btn-default btn-lg">

                            <i class="fa fa-arrow-left"></i>
                            Cancelar
                        </a>

                        <button type="submit"
                            class="btn btn-primary btn-lg">

                            <i class="fa fa-save"></i>
                            Guardar
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</section>