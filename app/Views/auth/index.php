<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gudang Parsial</title>
  <link rel="icon" href="<?= base_url() ?>/uploads/logo.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary" style="background-color: #bfa4a4;">
      <div class="card-header text-center">
        <a href="<?= base_url() ?>/index2.html" class="h1"><b>Gudang</b> Parsial</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">PT. Surya Citra Utama Mandiri</p>

        <?= form_open('auth/cekUser'); ?>
        <?= csrf_field(); ?>
        <div class="input-group mb-3">
          <?php
          $isInvalidUser = (session()->getFlashdata('errUserNama')) ? 'is-invalid' : '';
          ?>
          <input type="text" name="usernama" class="form-control <?= $isInvalidUser ?>" placeholder="Usernama" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <?php if (session()->getFlashdata('errUserNama')) {
            echo '<div class="invalid-feedback">
            ' . session()->getFlashdata('errUserNama') . '
          </div>';
          } ?>
        </div>
        <div class="input-group mb-3">
          <?php
            $isInvalidPassword = (session()->getFlashdata('errPassword')) ? 'is-invalid' : '';
          ?>
          <input type="password" name="userpassword" class="form-control <?= $isInvalidPassword ?>" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <?php if (session()->getFlashdata('errPassword')) {
            echo '<div class="invalid-feedback">
            ' . session()->getFlashdata('errPassword') . '
          </div>';
          } ?>
        </div>
        <div class="input-group mb-3">
          <button type="submit" , class="btn btn-block btn-primary">Login</button>
        </div>
        <?php form_close(); ?>


      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url() ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/dist/js/adminlte.min.js"></script>
</body>

</html>