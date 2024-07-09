<?php
$backgroud_img = $backgroud_img ?? false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <title>
          <?= $title ?? "Trang chủ" ?>
     </title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
     <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
     <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
     <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
     <link rel="shortcut icon" href="/assets/images/favicon.ico" />
     <link rel="manifest" href="/assets/images/site.webmanifest">
     <link rel="stylesheet" href="/assets/css/styles.css">

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     

</head>
<?php if (!$hide_header) : ?>
<nav class="navbar navbar-expand-sm bg-light  navbar-light fixed-top">
     <div class="container-fluid">
          <a class="navbar-brand" href="/">
               <img src="/assets/images/logo.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill">
               FGame
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
               <span class="navbar-toggler-icon"></span>
          </button>
          <form class="d-none d-md-flex">
               <input class="form-control me-2" type="text" placeholder="Tìm kiếm bài viết, sản phẩm">
               <button class="btn btn-primary" type="button">
                    <i class="fas fa-search"></i>
               </button>
          </form>
          <div class="collapse navbar-collapse justify-content-end ml-5" id="collapsibleNavbar">
               <form class="d-flex d-sm-none mt-3">
                    <input class="form-control me-2" type="text" placeholder="Tìm kiếm bài viết, sản phẩm">
                    <button class="btn btn-primary" type="button">
                         <i class="fas fa-search"></i>
                    </button>
               </form>
               <ul class="navbar-nav">
                    <li class="nav-item">
                         <a class="nav-link" href="/">
                              <i class="fas fa-home"></i>
                              Forum
                         </a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" href="#">
                              <i class="fa-solid fa-shop"></i>
                              Cửa Hàng
                         </a>
                    </li>

                    <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                              <i class="fas fa-user"></i> <?= $user->username ?? "Tài khoản" ?>
                         </a>
                         <ul class="dropdown-menu">
                              <?php if ($user) : ?>

                              <li><span class="dropdown-item" href="#">{!! $user->getRoles() !!}</span></li>
                              @if ($user->checkRole("mod"))
                                   <hr>
                                   <li>
                                        <a class="dropdown-item text-danger" style="font-weight: bold" href="{{ route("dashboard") }}">
                                             <i class="fas fa-tachometer-alt me-2"></i>
                                             Dashboard
                                        </a>
                                   </li>
                              @endif
                              <hr>
                              <li>
                                   <a class="dropdown-item" href="/profile">
                                        <i class="fas fa-user me-2"></i>
                                        Hồ sơ
                                   </a>
                              </li>
                              <li>
                                   <a class="dropdown-item" href="/logout">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Đăng xuất
                                   </a>
                              </li>
                              <?php else : ?>
                              <li>
                                   <a class="dropdown-item" href="/login">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Đăng nhập</a>
                              </li>
                              <li>
                                   <a class="dropdown-item" href="/register">
                                        <i class="fas fa-user-plus"></i>
                                        Đăng ký
                                   </a>
                              </li>
                              <?php endif; ?>
                         </ul>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" href="/">
                              <i class="fa-solid fa-cart-shopping"></i>
                              Giỏ hàng
                         </a>
                    </li>
               </ul>
          </div>
     </div>
</nav>
<?php endif; ?>

<div class="container" style="margin-top: 100px;"></div>
<!-- background full scren autp fill none-rp -->
<?php if ($backgroud_img) : ?>
<style>
     div#bg {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: -1;
     }
</style>
<div id="bg"><img width="100%" height="100%" src="<?= $backgroud_img ?>" /></div>
<?php endif; ?>

<body>
