<?php
require_once __DIR__ . '/../../Models/Conexion.php';

$errors  = [];
$success = false;

$nombres = $apellidos = '';
$telefono_movil = $telefono_casa = '';
$correo = $descripcion_grupo = '';
$fecha_cumpleanios = $observaciones = '';

$id_empresa = '';
$id_operador = '';
$id_grupo = '';

$pdo = Conexion::conectar();

$empresas = $pdo->query("
    SELECT id_empresa, nombre_empresa 
    FROM empresa 
    ORDER BY nombre_empresa
")->fetchAll(PDO::FETCH_ASSOC);

$operadores = $pdo->query("
    SELECT id_operador, nombre_operador 
    FROM operador 
    ORDER BY nombre_operador
")->fetchAll(PDO::FETCH_ASSOC);

$grupos = $pdo->query("
    SELECT id_grupo, nombre_grupo 
    FROM grupo_contacto 
    ORDER BY nombre_grupo
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $nombres            = trim($_POST['nombres'] ?? '');
  $apellidos          = trim($_POST['apellidos'] ?? '');
  $telefono_movil     = trim($_POST['telefono_movil'] ?? '');
  $telefono_casa      = trim($_POST['telefono_casa'] ?? '');
  $correo             = trim($_POST['correo'] ?? '');
  $descripcion_grupo  = trim($_POST['descripcion_grupo'] ?? '');
  $fecha_cumpleanios  = trim($_POST['fecha_cumpleanios'] ?? '');
  $observaciones      = trim($_POST['observaciones'] ?? '');

  $id_empresa         = trim($_POST['id_empresa'] ?? '');
  $id_operador        = trim($_POST['id_operador'] ?? '');
  $id_grupo           = trim($_POST['id_grupo'] ?? '');

  // VALIDACIONES

  if ($nombres === '') {
    $errors['nombres'] = 'Ingrese los nombres.';
  }

  if ($apellidos === '') {
    $errors['apellidos'] = 'Ingrese los apellidos.';
  }

  if ($telefono_movil === '') {
    $errors['telefono_movil'] = 'Ingrese el teléfono móvil.';
  } elseif (!preg_match('/^\d{7,11}$/', $telefono_movil)) {
    $errors['telefono_movil'] = 'Debe contener entre 7 y 11 dígitos.';
  }

  if ($telefono_casa !== '' && !preg_match('/^\d{7,11}$/', $telefono_casa)) {
    $errors['telefono_casa'] = 'Debe contener entre 7 y 11 dígitos.';
  }

  if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errors['correo'] = 'Correo inválido.';
  }

  if ($id_empresa === '') {
    $errors['id_empresa'] = 'Seleccione una empresa.';
  }

  if ($id_operador === '') {
    $errors['id_operador'] = 'Seleccione un operador.';
  }

  if ($id_grupo === '') {
    $errors['id_grupo'] = 'Seleccione un grupo.';
  }

  // INSERTAR

  if (empty($errors)) {

    $stmt = $pdo->prepare("
            INSERT INTO contacto
            (
                nombres,
                apellidos,
                id_empresa,
                id_operador,
                id_grupo,
                telefono_movil,
                telefono_casa,
                correo,
                descripcion_grupo,
                fecha_cumpleanios,
                observaciones
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $stmt->execute([
      $nombres,
      $apellidos,
      $id_empresa,
      $id_operador,
      $id_grupo,
      $telefono_movil,
      $telefono_casa ?: null,
      $correo ?: null,
      $descripcion_grupo ?: null,
      $fecha_cumpleanios ?: null,
      $observaciones ?: null
    ]);

    $success = true;

    $nombres = $apellidos = '';
    $telefono_movil = $telefono_casa = '';
    $correo = $descripcion_grupo = '';
    $fecha_cumpleanios = $observaciones = '';

    $id_empresa = '';
    $id_operador = '';
    $id_grupo = '';
  }
}
?>

<section class="content">

  <div class="row">

    <div class="col-md-8 col-md-offset-2">

      <div class="box box-primary">

        <div class="box-header with-border bg-blue">

          <h3 class="box-title text-white">

            <i class="fa fa-address-book"></i>
            Nuevo Contacto

          </h3>

        </div>

        <?php if ($success): ?>

          <div class="alert alert-success alert-dismissible margin">

            <button type="button"
              class="close"
              data-dismiss="alert">

              &times;

            </button>

            <i class="fa fa-check-circle"></i>

            <strong>¡Éxito!</strong>
            El contacto fue registrado correctamente.

          </div>

        <?php endif; ?>

        <form method="POST">

          <div class="box-body">

            <!-- NOMBRES -->

            <div class="row">

              <div class="col-md-6">

                <div class="form-group <?= isset($errors['nombres']) ? 'has-error' : '' ?>">

                  <label>
                    <i class="fa fa-user"></i>
                    Nombres
                  </label>

                  <input type="text"
                    class="form-control input-lg"
                    name="nombres"
                    maxlength="80"
                    placeholder="Ingrese nombres"
                    value="<?= htmlspecialchars($nombres) ?>">

                  <?php if (isset($errors['nombres'])): ?>

                    <span class="help-block">
                      <?= $errors['nombres'] ?>
                    </span>

                  <?php endif; ?>

                </div>

              </div>

              <div class="col-md-6">

                <div class="form-group <?= isset($errors['apellidos']) ? 'has-error' : '' ?>">

                  <label>
                    <i class="fa fa-user"></i>
                    Apellidos
                  </label>

                  <input type="text"
                    class="form-control input-lg"
                    name="apellidos"
                    maxlength="80"
                    placeholder="Ingrese apellidos"
                    value="<?= htmlspecialchars($apellidos) ?>">

                  <?php if (isset($errors['apellidos'])): ?>

                    <span class="help-block">
                      <?= $errors['apellidos'] ?>
                    </span>

                  <?php endif; ?>

                </div>

              </div>

            </div>

            <!-- TELEFONOS -->

            <div class="row">

              <div class="col-md-6">

                <div class="form-group <?= isset($errors['telefono_movil']) ? 'has-error' : '' ?>">

                  <label>
                    <i class="fa fa-mobile"></i>
                    Teléfono Móvil
                  </label>

                  <input type="text"
                    class="form-control input-lg"
                    name="telefono_movil"
                    maxlength="11"
                    placeholder="Ingrese teléfono móvil"
                    value="<?= htmlspecialchars($telefono_movil) ?>">

                </div>

              </div>

              <div class="col-md-6">

                <div class="form-group <?= isset($errors['telefono_casa']) ? 'has-error' : '' ?>">

                  <label>
                    <i class="fa fa-phone"></i>
                    Teléfono Casa
                  </label>

                  <input type="text"
                    class="form-control input-lg"
                    name="telefono_casa"
                    maxlength="11"
                    placeholder="Ingrese teléfono casa"
                    value="<?= htmlspecialchars($telefono_casa) ?>">

                </div>

              </div>

            </div>

            <!-- CORREO -->

            <div class="form-group <?= isset($errors['correo']) ? 'has-error' : '' ?>">

              <label>
                <i class="fa fa-envelope"></i>
                Correo
              </label>

              <input type="email"
                class="form-control input-lg"
                name="correo"
                maxlength="90"
                placeholder="Ingrese correo"
                value="<?= htmlspecialchars($correo) ?>">

            </div>

            <!-- SELECTS -->

            <div class="row">

              <div class="col-md-4">

                <div class="form-group">

                  <label>
                    <i class="fa fa-building"></i>
                    Empresa
                  </label>

                  <select name="id_empresa"
                    class="form-control input-lg">

                    <option value="">
                      Seleccione
                    </option>

                    <?php foreach ($empresas as $e): ?>

                      <option value="<?= $e['id_empresa'] ?>"
                        <?= $id_empresa == $e['id_empresa'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($e['nombre_empresa']) ?>

                      </option>

                    <?php endforeach; ?>

                  </select>

                </div>

              </div>

              <div class="col-md-4">

                <div class="form-group">

                  <label>
                    <i class="fa fa-phone-square"></i>
                    Operador
                  </label>

                  <select name="id_operador"
                    class="form-control input-lg">

                    <option value="">
                      Seleccione
                    </option>

                    <?php foreach ($operadores as $o): ?>

                      <option value="<?= $o['id_operador'] ?>"
                        <?= $id_operador == $o['id_operador'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($o['nombre_operador']) ?>

                      </option>

                    <?php endforeach; ?>

                  </select>

                </div>

              </div>

              <div class="col-md-4">

                <div class="form-group">

                  <label>
                    <i class="fa fa-users"></i>
                    Grupo
                  </label>

                  <select name="id_grupo"
                    class="form-control input-lg">

                    <option value="">
                      Seleccione
                    </option>

                    <?php foreach ($grupos as $g): ?>

                      <option value="<?= $g['id_grupo'] ?>"
                        <?= $id_grupo == $g['id_grupo'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($g['nombre_grupo']) ?>

                      </option>

                    <?php endforeach; ?>

                  </select>

                </div>

              </div>

            </div>

            <!-- FECHA -->

            <div class="form-group">

              <label>
                <i class="fa fa-calendar"></i>
                Fecha Cumpleaños
              </label>

              <input type="date"
                class="form-control input-lg"
                name="fecha_cumpleanios"
                value="<?= htmlspecialchars($fecha_cumpleanios) ?>">

            </div>

            <!-- DESCRIPCION -->

            <div class="form-group">

              <label>
                <i class="fa fa-tag"></i>
                Descripción Grupo
              </label>

              <input type="text"
                class="form-control input-lg"
                name="descripcion_grupo"
                maxlength="100"
                placeholder="Ingrese descripción"
                value="<?= htmlspecialchars($descripcion_grupo) ?>">

            </div>

            <!-- OBSERVACIONES -->

            <div class="form-group">

              <label>
                <i class="fa fa-sticky-note"></i>
                Observaciones
              </label>

              <textarea class="form-control input-lg"
                name="observaciones"
                rows="4"
                placeholder="Ingrese observaciones"><?= htmlspecialchars($observaciones) ?></textarea>

            </div>

          </div>

          <div class="box-footer text-right">

            <a href="index.php?Pages=Listar_Contacto"
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