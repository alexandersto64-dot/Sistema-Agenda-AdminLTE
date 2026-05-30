<section class="content">

  <div class="box">

    <div class="box-header">
      <h3 class="box-title">Mi Perfil</h3>
    </div>

    <div class="box-body text-center">

      <img src="<?= $_SESSION["foto"] ?? 'Views/Images/Users/default.jpg' ?>"
           class="img-circle"
           width="100">

      <h3><?= $_SESSION["nombre"] ?></h3>

      <p><?= $_SESSION["rol"] ?></p>

    </div>

  </div>

</section>