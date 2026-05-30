<section class="content">

  <div class="row">

    <div class="col-md-10 col-md-offset-1">

      <div class="box box-info">

        <div class="box-header text-center">
          <h3 class="box-title">
            <i class="fa fa-user"></i> Mi Perfil
          </h3>
        </div>

        <div class="box-body text-center">

          <img src="<?= $_SESSION["foto"] ?? 'Views/Images/Users/default.jpg' ?>"
               class="img-circle"
               width="110"
               style="border:3px solid #ddd; box-shadow:0 4px 10px rgba(0,0,0,0.1);">

          <h3 style="margin-top:15px; font-weight:700;">
            <?= htmlspecialchars($_SESSION["nombre"] ?? '') ?>
          </h3>

          <span class="label label-primary" style="font-size:13px; padding:6px 12px;">
            <?= htmlspecialchars($_SESSION["rol"] ?? '') ?>
          </span>

        </div>

      </div>

    </div>

  </div>

</section>