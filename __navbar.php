  <nav class="navbar sb-topnav navbar-expand bg-dark navbar-dark">
    <a class="navbar-brand" href="index"><?=$navName;?></a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="profil"><?=$_SESSION['log']['nama'];?></a>
              <a class="dropdown-item" href="change-password">Ganti Kata Sandi</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout">Logout</a>
          </div>
        </li>
      </ul>
    <div>
  </nav>