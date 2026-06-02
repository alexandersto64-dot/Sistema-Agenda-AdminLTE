<?php
require_once __DIR__ . '/../../Models/Conexion.php';

$errors  = [];
$success = false;
$nombres = $apellidos = $telefono_movil = $telefono_casa = '';
$correo  = $descripcion_grupo = $fecha_cumpleanios = $observaciones = '';
$id_empresa = $id_operador = $id_grupo = '';

$pdo        = Conexion::conectar();
$empresas   = $pdo->query("SELECT id_empresa, nombre_empresa FROM empresa ORDER BY nombre_empresa")->fetchAll(PDO::FETCH_ASSOC);
$operadores = $pdo->query("SELECT id_operador, nombre_operador FROM operador ORDER BY nombre_operador")->fetchAll(PDO::FETCH_ASSOC);
$grupos     = $pdo->query("SELECT id_grupo, nombre_grupo FROM grupo_contacto ORDER BY nombre_grupo")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombres           = trim($_POST['nombres']           ?? '');
    $apellidos         = trim($_POST['apellidos']         ?? '');
    $telefono_movil    = trim($_POST['telefono_movil']    ?? '');
    $telefono_casa     = trim($_POST['telefono_casa']     ?? '');
    $correo            = trim($_POST['correo']            ?? '');
    $descripcion_grupo = trim($_POST['descripcion_grupo'] ?? '');
    $fecha_cumpleanios = trim($_POST['fecha_cumpleanios'] ?? '');
    $observaciones     = trim($_POST['observaciones']     ?? '');
    $id_empresa        = trim($_POST['id_empresa']        ?? '');
    $id_operador       = trim($_POST['id_operador']       ?? '');
    $id_grupo          = trim($_POST['id_grupo']          ?? '');

    // Validaciones
    if ($nombres === '')
        $errors['nombres'] = 'Por favor ingrese los nombres.';
    elseif (strlen($nombres) > 80)
        $errors['nombres'] = 'Máximo 80 caracteres.';

    if ($apellidos === '')
        $errors['apellidos'] = 'Por favor ingrese los apellidos.';
    elseif (strlen($apellidos) > 80)
        $errors['apellidos'] = 'Máximo 80 caracteres.';

    if ($telefono_movil === '')
        $errors['telefono_movil'] = 'Por favor ingrese el teléfono móvil.';
    elseif (!preg_match('/^\d{7,11}$/', $telefono_movil))
        $errors['telefono_movil'] = 'El teléfono debe contener entre 7 y 11 dígitos.';

    if ($telefono_casa !== '' && !preg_match('/^\d{7,11}$/', $telefono_casa))
        $errors['telefono_casa'] = 'El teléfono de casa debe contener entre 7 y 11 dígitos.';

    if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL))
        $errors['correo'] = 'El correo electrónico no es válido.';

    if ($id_empresa === '')
        $errors['id_empresa'] = 'Seleccione una empresa.';

    if ($id_operador === '')
        $errors['id_operador'] = 'Seleccione un operador.';

    if ($id_grupo === '')
        $errors['id_grupo'] = 'Seleccione un grupo.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO contacto
                (nombres, apellidos, id_empresa, id_operador, id_grupo,
                 telefono_movil, telefono_casa, correo,
                 descripcion_grupo, fecha_cumpleanios, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $nombres, $apellidos, $id_empresa, $id_operador, $id_grupo,
            $telefono_movil,
            $telefono_casa     ?: null,
            $correo            ?: null,
            $descripcion_grupo ?: null,
            $fecha_cumpleanios ?: null,
            $observaciones     ?: null,
        ]);
        $success = true;
        $nombres = $apellidos = $telefono_movil = $telefono_casa = '';
        $correo  = $descripcion_grupo = $fecha_cumpleanios = $observaciones = '';
        $id_empresa = $id_operador = $id_grupo = '';
    }
}
?>

<section class="content">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
<<<<<<< Updated upstream
      <div class="box box-primary">

        <div div class="box-header with-border bg-blue">
          <h3 class="box-title text-white">
=======
      <div class="box box-info">

        <div class="box-header with-border">
          <h3 class="box-title">
>>>>>>> Stashed changes
            <i class="fa fa-address-book"></i> Nuevo Contacto
          </h3>
        </div>

        <?php if ($success): ?>
          <div class="alert alert-success alert-dismissible margin">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check-circle"></i>
            <strong>¡Éxito!</strong> El contacto fue registrado correctamente.
          </div>
        <?php endif; ?>

        <form method="POST">
          <div class="box-body">

            <!-- Nombres y Apellidos -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group <?= isset($errors['nombres']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-user"></i> Nombres</label>
                  <input type="text" class="form-control" name="nombres"
                    placeholder="Ingrese nombres" maxlength="80"
                    value="<?= htmlspecialchars($nombres) ?>">
                  <?php if (isset($errors['nombres'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['nombres'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group <?= isset($errors['apellidos']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-user"></i> Apellidos</label>
                  <input type="text" class="form-control" name="apellidos"
                    placeholder="Ingrese apellidos" maxlength="80"
                    value="<?= htmlspecialchars($apellidos) ?>">
                  <?php if (isset($errors['apellidos'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['apellidos'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Teléfonos -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group <?= isset($errors['telefono_movil']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-mobile"></i> Teléfono Móvil</label>
                  <input type="text" class="form-control" name="telefono_movil"
                    placeholder="Ej: 987654321" maxlength="11"
                    value="<?= htmlspecialchars($telefono_movil) ?>">
                  <?php if (isset($errors['telefono_movil'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['telefono_movil'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group <?= isset($errors['telefono_casa']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-phone"></i> Teléfono Casa <small class="text-muted">(opcional)</small></label>
                  <input type="text" class="form-control" name="telefono_casa"
                    placeholder="Ej: 014785236" maxlength="11"
                    value="<?= htmlspecialchars($telefono_casa) ?>">
                  <?php if (isset($errors['telefono_casa'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['telefono_casa'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Correo y Fecha cumpleaños -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group <?= isset($errors['correo']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-envelope"></i> Correo <small class="text-muted">(opcional)</small></label>
                  <input type="email" class="form-control" name="correo"
                    placeholder="ejemplo@correo.com" maxlength="90"
                    value="<?= htmlspecialchars($correo) ?>">
                  <?php if (isset($errors['correo'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['correo'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fa fa-birthday-cake"></i> Fecha de Cumpleaños <small class="text-muted">(opcional)</small></label>
                  <input type="date" class="form-control" name="fecha_cumpleanios"
                    value="<?= htmlspecialchars($fecha_cumpleanios) ?>">
                </div>
              </div>
            </div>

            <!-- Empresa, Operador, Grupo -->
            <div class="row">
              <div class="col-md-4">
                <div class="form-group <?= isset($errors['id_empresa']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-building"></i> Empresa</label>
                  <select class="form-control" name="id_empresa">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($empresas as $e): ?>
                      <option value="<?= $e['id_empresa'] ?>"
                        <?= $id_empresa == $e['id_empresa'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['nombre_empresa']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (isset($errors['id_empresa'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['id_empresa'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group <?= isset($errors['id_operador']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-phone-square"></i> Operador</label>
                  <select class="form-control" name="id_operador">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($operadores as $o): ?>
                      <option value="<?= $o['id_operador'] ?>"
                        <?= $id_operador == $o['id_operador'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($o['nombre_operador']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (isset($errors['id_operador'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['id_operador'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group <?= isset($errors['id_grupo']) ? 'has-error' : '' ?>">
                  <label><i class="fa fa-users"></i> Grupo</label>
                  <select class="form-control" name="id_grupo">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($grupos as $g): ?>
                      <option value="<?= $g['id_grupo'] ?>"
                        <?= $id_grupo == $g['id_grupo'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($g['nombre_grupo']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (isset($errors['id_grupo'])): ?>
                    <span class="help-block"><i class="fa fa-exclamation-circle"></i> <?= $errors['id_grupo'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Descripción de grupo -->
            <div class="form-group">
              <label><i class="fa fa-tag"></i> Descripción del Grupo <small class="text-muted">(opcional)</small></label>
              <input type="text" class="form-control" name="descripcion_grupo"
                placeholder="Ej: Compañero de trabajo, Cliente frecuente..." maxlength="100"
                value="<?= htmlspecialchars($descripcion_grupo) ?>">
            </div>

            <!-- Observaciones -->
            <div class="form-group">
              <label><i class="fa fa-sticky-note"></i> Observaciones <small class="text-muted">(opcional)</small></label>
              <textarea class="form-control" name="observaciones" rows="3"
                placeholder="Ingrese observaciones adicionales..."><?= htmlspecialchars($observaciones) ?></textarea>
            </div>

          </div><!-- /.box-body -->

          <div class="box-footer text-right">
            <a href="index.php?Pages=Listar_Contacto" class="btn btn-default btn-lg">
              <i class="fa fa-arrow-left"></i> Cancelar
            </a>
<<<<<<< Updated upstream
            <button type="submit" class="btn btn-primary  btn-lg">
=======
            <button type="submit" class="btn btn-info btn-lg">
>>>>>>> Stashed changes
              <i class="fa fa-save"></i> Guardar
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>