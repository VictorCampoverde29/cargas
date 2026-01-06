<?=  $this->extend('dashboard/template'); ?>
<?=  $this->section('content'); ?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-danger">500</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! No cuenta con acceso.</h3>

          <p>
            No tiene acceso para estar aqui.
            <a href="<?= base_url()?>">Regresar al Inicio </a> o comuniquese con el area de sistemas mediante un correo. 
            <a href="mailto:sistemas@grupoasiu.com?subject=Soporte%20Sistemas" class="btn btn-primary">
                <i class="fas fa-envelope"></i> Contactar a Sistemas
            </a>
          </p>

         
        </div>
      </div>
      <!-- /.error-page -->

    </section>
  <?= $this->endSection() ?>
