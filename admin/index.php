<?php
require '../src/db.config.php';
require './pages.php';
ob_start();
session_start();
if(!isset($_SESSION["pollsystem-admin"])){
  header("location:signin.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    pollSystem Powered By BaydoganMirac
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="../css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="../css/dashboard.css" rel="stylesheet" />
  <!--   Core JS Files   -->
  <script src="../js/core/jquery.min.js"></script>
  <script src="../js/core/popper.min.js"></script>
  <script src="../js/core/bootstrap.min.js"></script>
  <script src="../js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Chart JS -->
  <script src="../js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../js/plugins/bootstrap-notify.js"></script>
    <!-- mycss -->
  <link href="../css/admin.css" rel="stylesheet">
</head>

<body class="">
  <div class="wrapper">
    <div class="sidebar">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
    -->
      <div class="sidebar-wrapper">
        <div class="logo">
          <a href="javascript:void(0)" class="simple-text logo-mini">
            PS
          </a>
          <a href="javascript:void(0)" class="simple-text logo-normal">
            pollSystem
          </a>
        </div>
        <ul class="nav">

          <li <?php $page = @$_GET["page"]; if($page == "" || $page == "homepage"){echo "class='active'";}?>>
            <a href="homepage.html">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li <?php $page = @$_GET["page"]; if($page == "polls"){echo "class='active'";}?>>
            <a href="polls.html">
              <i class="tim-icons icon-notes"></i>
              <p>Anketler</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)"><?php
            if ($page =="homepage") {
              echo "Dashboard";
            } else if($page == "polls") {
              echo "Anketler";
            }else if($page == "profile"){
              echo "Profil";
            }else if($page == "settings"){
              echo "Ayarlar";
            }else{
              echo "Welcome";
            }
            ?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
              <li class="dropdown nav-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="photo">
                    <img src="../img/admin.png" alt="Profile Photo">
                  </div>
                  <b class="caret d-none d-lg-block d-xl-block"></b>
                  <p class="d-lg-none">
                    Çıkış Yap
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-navbar">
                  <li class="nav-link">
                    <a href="settings.html" class="nav-item dropdown-item">Ayarlar</a>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li class="nav-link">
                    <a href="signout.html" class="nav-item dropdown-item">Çıkış Yap</a>
                  </li>
                </ul>
              </li>
              <li class="separator d-lg-none"></li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <?php 
          $page = @$_GET["page"];
          if($page==""){
            require $pages['homepage'];
          }else{
            require $pages[$page];
          }
        ?>
        </div>
      <!-- Start Footer -->
      <footer class="footer">
        <div class="container-fluid">
          <ul class="nav">
            <li class="nav-item">
              <a href="https://baydoganmirac.net/hakkimda.html" class="nav-link">
                Hakkımda
              </a>
            </li>
            <li class="nav-item">
              <a href="https://baydoganmirac.net/blog.html" class="nav-link">
                Blog
              </a>
            </li>
          </ul>
          <div class="copyright">
            ©
            <script>
              document.write(new Date().getFullYear())
            </script> <i class="tim-icons icon-heart-2"></i> by
            <a href="https://baydoganmirac.net/" target="_blank">BaydoganMirac</a>.
          </div>
        </div>
      </footer>
    </div>
  </div>
          <div class="fixed-top">
            V1.000
        </div>
</body>

</html>