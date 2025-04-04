<nav class="navbar col-lg-12 col-12 p-lg-0 fixed-top d-flex flex-row">
  <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
    <a class="navbar-brand brand-logo-mini align-self-center d-lg-none" href="index.html"><img src="<?= site_url();?>assets/images/nu-logo.png" alt="logo" style="width: 50px; height: auto;" /></a>
    <button class="navbar-toggler navbar-toggler align-self-center mr-2" type="button" data-toggle="minimize">
      <i class="mdi mdi-menu"></i>
    </button>
    <ul class="navbar-nav navbar-nav-right ml-lg-auto">
      <li class="nav-item nav-profile dropdown border-0">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown">
          <img class="nav-profile-img mr-2" alt="" src="<?= site_url();?>assets/images/admin.png" />
          <span class="profile-name">Trilogy Admin</span>
        </a>
        <div class="dropdown-menu navbar-dropdown w-100" aria-labelledby="profileDropdown">
          <!--<a class="dropdown-item" href="<?= site_url();?>admin/profile">-->
          <!--  <i class="mdi mdi-cached mr-2 text-success"></i> Set Profile </a>-->
          <a class="dropdown-item" href="<?= site_url();?>admin/signout">
            <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>