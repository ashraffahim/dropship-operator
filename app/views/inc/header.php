<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
  <title>Dopamine</title>
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="icon" href="/dopamine/images/favicon.png" type="image/x-icon">

  <link rel="stylesheet" href="/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/dopamine/css/style.css">

  <script src="/jquery/dist/jquery.min.js"></script>
  <script src="/tether/dist/js/tether.min.js"></script>
  <script src="/bootstrap/dist/js/popper.min.js"></script>
  <script src="/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="/dopamine/js/form.js"></script>
  <script src="/dopamine/js/module.config.js"></script>
  <script src="/dopamine/js/script.js"></script>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light p-0">
      <div class="navbar-brand pl-3 bg-theme text-light">
        <a href="/Home" class="page-logo-link position-relative lead">
          <!-- <img src="/dopamine/images/dopamine-brand.png" height="38"> -->
          metabók
        </a>
      </div>
      <div class="row navbar-row">
        
        <!-- Task Bar -->

        <div class="col dock">
          <button class="navbar-toggler border-0 d-inline-block" type="button" onclick="$('html').toggleClass('closed');$('.navbar-brand').toggleClass('bg-theme text-light');" aria-controls="html" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>

        <!-- Navigation Dropdown -->

        <div class="navbar-dialogue col-2 d-inline-flex align-items-center justify-content-end">

          <!-- Newspaper -->

          <div class="dropdown">
            <button type="button" class="btn btn-icon" data-toggle="dropdown">
              <i class="far fa-newspaper fa-lg"></i>
              <span class="position-absolute badge badge-danger rounded-pill">1</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            </div>
          </div>

          <!-- Bell -->

          <div class="dropdown">
            <button type="button" class="btn btn-icon" data-toggle="dropdown"><i class="far fa-bell fa-lg"></i></button>
            <div class="dropdown-menu dropdown-menu-right">
            </div>
          </div>

          <!-- User Actions -->

          <div class="dropdown">
            <button type="button" class="btn btn-icon" data-toggle="dropdown"><i class="far fa-user fa-lg"></i></button>
            <div class="dropdown-menu dropdown-menu-right p-0">
              <a class="dropdown-item" href="/user/profile" data-toggle="load-host" data-target="#content">
                <span class="btn btn-icon btn-xs mr-2"><i class="far fa-user fa-lg"></i></span><span>Profile</span>
              </a>
              <a class="dropdown-item" href="javascript:void(0)" onclick="changeThemeMode(this)">
                <span class="btn btn-icon btn-xs mr-2"><i class="fa fa-adjust fa-lg"></i></span><span class="change-theme-mode">Light mode</span>
              </a>
              <a class="dropdown-item" href="javascript:void(0)" onclick="fullscreen(this)">
                <span class="btn btn-icon btn-xs mr-2"><i class="fa fa-expand fa-lg"></i></span>
                <span>Enter fullscreen <i class="text-muted float-right">f11</i></span>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/Login/logout">
                <span class="btn btn-icon btn-xs mr-2"><i class="fa fa-level-up-alt fa-lg"></i></span><span>Logout</span>
              </a>
            </div>
          </div>
        
        </div>

      </div>
    </nav>

    <div class="container-fluid">
      <div class="row position-relative">
        <aside class="page-sidebar bg-theme-dark" id="navbarSupportedContent">
          <div class="sidebar-sticky">
            <ul class="nav flex-column d-sm-none">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link" onclick="$('html').toggleClass('closed');$('.navbar-brand').toggleClass('bg-theme text-light');" aria-controls="html" aria-label="Toggle navigation">
                <span class="btn-icon btn-xs mr-2"><i class="fa fa-arrow-left fa-lg"></i></span><span>Hide Navigation</span>
                </a>
              </li>
            </ul>
            <?php include 'sidebar.php'; ?>
          </div>
        </aside>

        <main id="content" role="main" class="main-content position-relative px-4 pt-4">