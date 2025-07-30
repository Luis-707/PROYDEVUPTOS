<?php
   include_once "controlador/config/configuracion.php";
?>
<!doctype html>

<html
  lang="es"
  class=" layout-navbar-fixed layout-menu-fixed layout-compact "
  dir="ltr"
  data-skin="default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-semi-dark"
  data-bs-theme="light">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Demo: Dashboard</title>
    <!-- Favicon -->
    <!-- <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" /> -->
    <link rel="shortcut icon" type="image/png" href="img/logo/logo.png"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->
    <link rel="stylesheet" href="assets/vendor/libs/pickr/pickr-themes.css" />
    
    <link rel="stylesheet" href="assets/vendor/css/core.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    
    <!-- Vendors CSS -->
    
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    
    <!-- endbuild -->

    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Datatable css -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <script src="controlador/js/proceso.js"></script>
    
  </head>

  <body>
    
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
   
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu" data-bs-theme="dark">
            
            <div class="app-brand demo " id="banner"> 
                <a href="index.html" class="app-brand-link"> 
                    <span class="app-brand-logo demo">
                        <span class="text-primary"> 
                            <img src="img/logo/logo.png" alt="logo" style="width: 50px; max-height: 100%;"> 
                        </span>
                    </span>
                    <span class="app-brand-text menu-text fw-bold ms-2"><?php echo SISTEMA; ?></span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                    <i class="icon-base bx bx-chevron-left"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            
            
            <?php include_once "views/menu.php" ?>
                
            
            </aside>

    

<!-- Layout container -->
<div class="layout-page">
      
    <!-- Navbar -->

    <nav class="layout-navbar container-fluid navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" data-bs-theme="dark" id="layout-navbar">
    
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
        <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>


        <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
            
            <ul class="navbar-nav flex-row align-items-center ms-md-auto">

                <!-- Style Switcher -->
                <li class="nav-item dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="icon-base bx bx-sun icon-md theme-icon-active"></i>
                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                    <li>
                    <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                        <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Light</span>
                    </button>
                    </li>
                    <li>
                    <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                        <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Dark</span>
                    </button>
                    </li>
                    <li>
                    <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                        <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>System</span>
                    </button>
                    </li>
                </ul>
                </li>
                <!-- / Style Switcher-->
        
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="assets/img/avatars/1.png" alt class="rounded-circle" />
                    </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-account.html">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                                <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                            </div>
                            <div class="flex-grow-1">
                            <h6 class="mb-0">John Doe</h6>
                            <small class="text-body-secondary">Admin</small>
                            </div>
                        </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-profile-user.html"> <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span> </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-account.html"> <i class="icon-base bx bx-cog icon-md me-3"></i><span>Settings</span> </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-billing.html">
                        <span class="d-flex align-items-center align-middle">
                            <i class="flex-shrink-0 icon-base bx bx-credit-card icon-md me-3"></i><span class="flex-grow-1 align-middle">Billing Plan</span>
                            <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                        </span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-pricing.html"> <i class="icon-base bx bx-dollar icon-md me-3"></i><span>Pricing</span> </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-faq.html"> <i class="icon-base bx bx-help-circle icon-md me-3"></i><span>FAQ</span> </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="auth-login-cover.html" target="_blank"> <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span> </a>
                    </li>
                    </ul>
                </li>
                <!--/ User -->
                
            </ul>
        </div>

    </nav>

    <!-- / Navbar -->
      

      <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-fluid flex-grow-1 container-p-y" id="cuerpo">
            
            <!-- aqui va el contenido de las paginas -->
            
            <div class="row mb-12 g-6" id= "CentroPrinci">
                <div class="col-md-12 col-lg-12">
                <h6 class="mt-2 text-body-secondary">Título de la página</h6>
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="mb-1 me-2">Titulo del panel</h5>
                          <p class="card-subtitle">subtitulo del panel</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            This is some text within a card body. Jelly lemon drops tiramisu chocolate cake cotton candy soufflé oat cake sweet roll. Sugar plum marzipan dragée topping cheesecake chocolate bar. Danish muffin icing donut.
                        </p>
                        <p>
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloribus labore, non saepe assumenda rem deleniti, architecto repellendus nobis minima temporibus eligendi dolorem sed? Perspiciatis quaerat voluptatum harum adipisci sequi magni non temporibus corporis magnam! Dolor, nobis exercitationem tenetur officiis omnis quas architecto ullam nihil animi suscipit ratione fuga veniam placeat consequuntur porro nostrum! Sapiente cupiditate ipsum quo ratione ullam? Accusantium rem iusto vitae nemo vero voluptatum voluptas itaque, officia ipsum temporibus veritatis eaque non. Distinctio enim quibusdam assumenda dignissimos sequi doloremque ipsa porro beatae, officia dolorum? Perferendis, iure? Cumque sit iusto nihil! Totam, recusandae. Veniam eveniet nulla iusto fugit vitae.
                        </p>
                    </div>
                </div>
                </div>
            </div>

        </div>
        <!-- / Content -->
        
        

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    ©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    información de pie de página
                </div>
                <div class="d-none d-lg-inline-block">
                    
                    <a href="#" class="footer-link me-4" target="_blank">License</a>
                    <a href="#" target="_blank" class="footer-link me-4">More Themes</a>
                    <a href="#" target="_blank" class="footer-link me-4">Documentation</a>            
                    <a href="#" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
                    
                </div>
                </div>
            </div>
        </footer>
        <!-- / Footer -->

        
        <!-- <div class="content-backdrop fade"></div> -->
    </div>
    <!-- Content wrapper -->

</div>
<!-- Layout container -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->
    
    
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/@algolia/autocomplete-js.js"></script>

    
      
    <script src="assets/vendor/libs/pickr/pickr.js"></script>
    

    
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
      
        
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
        
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
        
      
    <script src="assets/vendor/js/menu.js"></script>
    
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    
    <script src="assets/js/main.js"></script>
    

    <!-- Page JS -->
    <script src="assets/js/dashboards-crm.js"></script>

    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <script src="views/js/event_menu.js"></script>
    <script src="js/siglab.js"></script>
    <script src="views/js/usuario.js"></script>
    
  </body>
</html>

  <!-- beautify ignore:end -->
