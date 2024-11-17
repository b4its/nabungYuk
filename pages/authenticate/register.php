<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register | Nabung Yuk</title>
  <link rel="stylesheet" href="../../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../../assets/images/favicon.png">
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <h3>Register</h3>
              <!-- Notifikasi -->
              <?php if (isset($_SESSION['messages'])): ?>
              <div class="alert alert-<?php echo $_SESSION['statusAlert']; ?>">
                <?php echo $_SESSION['messages']; unset($_SESSION['messages'], $_SESSION['statusAlert']); ?>
              </div>
              <?php endif; ?>
              <form action="proses/prosesRegister.php" method="POST" class="pt-3">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password1" placeholder="Password" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password2" placeholder="Confirm Password" required>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Register</button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="../../index.php" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="../../assets/js/jquery.js"></script>
<script src="../../assets/vendors/sweetalert/sweetalert.min.js"></script>
<?php
//notifikasi
include_once '../../helper/messages.php';
?>
  <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../../assets/js/off-canvas.js"></script>
  <script src="../../assets/js/hoverable-collapse.js"></script>
  <script src="../../assets/js/template.js"></script>
  <script src="../../assets/js/settings.js"></script>
  <script src="../../assets/js/todolist.js"></script>
</body>

</html>
