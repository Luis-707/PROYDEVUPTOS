<?php

session_start();
$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

   include_once "controlador/config/configuracion.php";

include_once "middleware/auth.php";
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
    <title>Dashboard</title>
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
    
    <style>

/* Fondo fijo detrás de TODO */
    .odi {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('img/I437.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            filter: blur(1px); /* Ligero difuminado */
            opacity: 0.4; /* Semitransparente */
            z-index: -1; /* DETRÁS de todo el contenido */
        }

        /* Título BIENVENIDO en CURSIVA */
        .hero-section {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .titulo-bienvenido {
            font-size: clamp(3rem, 8vw, 6rem); /* Responsive */
            font-weight: 300; /* Delgado para elegancia */
            color: black;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
            font-style: italic; /* CURSIVA */
            letter-spacing: 0.1em;
            margin-bottom: 1.5rem;
            font-family: 'Georgia', 'Times New Roman', serif; /* Fuente elegante cursiva */
        }

        .subtitulo {
            font-size: clamp(1.2rem, 4vw, 1.8rem);
            color: rgba(17, 16, 16, 0.95);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            font-weight: 400;
            max-width: 600px;
        }

        /* Resto de estilos existentes */
        .btn-float {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 10;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 1.2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-content {
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-header {
            border-bottom: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .titulo-bienvenido {
                letter-spacing: 0.05em;
                padding: 0 1rem;
            }
        }

        /* Título del modal en BLANCAS y bien posicionado */
        /* Título del modal ELEVADO */
        .modal-title {
            font-weight: bold !important;
            color: white !important;
        }

        /* Botón AZUL FIJO sin cambios */
        .btn-float.btn-primary {
            background-color: #0d6efd !important; /* Azul primario fijo */
            border-color: #0d6efd !important; /* Borde fijo */
            color: white !important; /* Texto blanco fijo */
            opacity: 1 !important;
            filter: none !important;
        }

        .btn-float.btn-primary:hover,
        .btn-float.btn-primary:focus,
        .btn-float.btn-primary:active,
        .btn-float.btn-primary:visited {
            background-color: #0d6efd !important; /* ← SIEMPRE azul */
            border-color: #0d6efd !important;
            color: white !important; /* ← SIEMPRE blanco */
            opacity: 1 !important;
            filter: none !important;
            transform: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

    </style>

    
    <!-- Vendors CSS -->
    
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/sweetalert2/sweetalert2.css"/>
    <!-- endbuild -->

    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Datatable css -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    
    <!-- CHARTJS -->

    <script src="assets/vendor/libs/chartjs/chartjs.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/chartjs/chartjs.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <script src="controlador/js/proceso.js"></script>

    <!--tipografia-->
    <link rel="stylesheet" href="views/tipografia.css">


    <!--<script src="assets/vendor/libs/jspdf/jspdf.umd.min.js"></script>
    <script src="assets/vendor/libs/jspdf/jspdf.plugin.autotable.min.js"></script>-->

   <script src="jspdf.umd.min.js"></script>
    <script src="jspdf.plugin.autotable.min.js"></script> 
    
  </head>

  <body>
    
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
   
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu" data-bs-theme="dark">
            
            <div class="app-brand demo " id="banner"> 
                <a href="index.php" class="app-brand-link"> 
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
                        <a class="dropdown-item" href="index.php">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                                <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                            </div>
                            <div class="flex-grow-1">
                            <h6 class="mb-0"><?php echo $_SESSION['usuario']['nombre']; ?></h6>
                            <small class="text-body-secondary"><?php echo implode(', ', $_SESSION['usuario']['roles']?? []); ?></small>
                            </div>
                        </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="abrirPerfilUsuario('<?php echo $_SESSION['usuario']['cedula']; ?>')"> <i class="icon-base bx bx-user icon-md me-3"></i><span>Perfil</span> </a>
                    </li>
                    <!--<li>
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
                    </li>-->
                    <li>
                  <a class="dropdown-item" href="javascript:void(0)" onclick="logout()">
                     <i class="icon-base bx bx-power-off icon-md me-3"></i>
                     <span>Cerrar sesion</span>
                  </a>
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
            
            <!--<div class="row mb-12 g-6" id= "CentroPrinci">
                <div class="col-md-12 col-lg-12">
                <h6 class="mt-2 text-body-secondary"></h6>
                <div class="card mb-6">
                <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="mb-1 me-2">ODI</h5>
                          <p class="card-subtitle">(Objetivos de Desempeño Individual)</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Este es un sistema de información pensado para tener un seguimiento y control de las evaluaciones del personal administrativo y obrero de la Universidad Politécnica territorial del oeste de Sucre "Clodosbaldo Rússian" más eficiente.
                        </p>
                        <p>
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloribus labore, non saepe assumenda rem deleniti, architecto repellendus nobis minima temporibus eligendi dolorem sed? Perspiciatis quaerat voluptatum harum adipisci sequi magni non temporibus corporis magnam! Dolor, nobis exercitationem tenetur officiis omnis quas architecto ullam nihil animi suscipit ratione fuga veniam placeat consequuntur porro nostrum! Sapiente cupiditate ipsum quo ratione ullam? Accusantium rem iusto vitae nemo vero voluptatum voluptas itaque, officia ipsum temporibus veritatis eaque non. Distinctio enim quibusdam assumenda dignissimos sequi doloremque ipsa porro beatae, officia dolorum? Perferendis, iure? Cumque sit iusto nihil! Totam, recusandae. Veniam eveniet nulla iusto fugit vitae.
                        </p>
                    </div>
                </div>
                </div>
            </div>-->
            <div class="odi"></div>
            <section class="hero-section">
                <h1 class="titulo-bienvenido">Bienvenido</h1>
                <p class="subtitulo">Explora nuestro sistema de evaluación de desempeño</p>
            </section>
        
            <!-- Botón flotante -->
            <button class="btn btn-primary btn-float shadow-lg" data-bs-toggle="modal" data-bs-target="#modalDescripcion" title="Acerca del sitio">
                ℹ️
            </button>
        
            <!-- Modal (sin cambios) -->
            <div class="modal fade" id="modalDescripcion" tabindex="-1" aria-labelledby="modalDescripcionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDescripcionLabel">
                                <i class="bx bx-info-circle me-2"></i>
                                Acerca del Sitio
                            </h5>
                            <!--<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>-->
                        </div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <i class='bx bx-building display-1 text-primary mb-3'></i>
                                    <h6>Sistema administrativo</h6>
                                </div>                                
                                <div class="col-md-8">
                                    <h4 class="mb-3">Nombre del sistema</h4>
                                    <p class="lead">ODI (Objetivos de desempeño individual)</p>
                                    <h4 class="mb-3">Descripción del sistema</h4>
                                    <p class="lead">Plataforma desarrollada para la gestión eficiente de evaluaciones de empleados, roles jerárquicos y reportes administrativos.</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class='bx bx-check-circle text-success me-2'></i>Backend PHP 8.2 + PostgreSQL</li>
                                <li class="mb-2"><i class='bx bx-check-circle text-success me-2'></i>Sistema de roles y permisos</li>
                                <li class="mb-2"><i class='bx bx-check-circle text-success me-2'></i>Reportes en PDF con JSPDF</li>
                                    </ul>
                                    <p><small class="text-muted">Desarrollado por Deriek Ramos y Luis Galanton - UPTOS "Clodosbaldo Russián"</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Entendido</button>
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
                    <!--información de pie de página-->
                </div>
                <!--<div class="d-none d-lg-inline-block">
                    
                    <a href="#" class="footer-link me-4" target="_blank">License</a>
                    <a href="#" target="_blank" class="footer-link me-4">More Themes</a>
                    <a href="#" target="_blank" class="footer-link me-4">Documentation</a>            
                    <a href="#" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
                    
                </div>-->
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

    <script src="assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
      
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
    <!--<script src="views/js/usuario.js"></script>-->
    <script src="views/js/form_user.js"></script>
    <script src="views/js/evaluadores.js"></script>
    <script src="views/js/supervisores.js"></script>
    <script src="views/js/comentario_evaluado.js"></script>
    <script src="views/js/comentario_supervisor.js"></script> 
    <script src="views/js/comentario_evaluado_obreros.js"></script>
    <script src="views/js/comentario_supervisor_obreros.js"></script>
    <!--<script src="views/js/asignar_evaluador.js"></script>-->
    <script src="views/js/evaluados.js"></script>
    <script src="views/js/evaluados_obreros.js"></script>
    <script src="views/js/evaluados_comentarios.js"></script>
    <script src="views/js/evaluados_comentarios_obreros.js"></script>
    <script src="views/js/evaluados_resultados.js"></script>
    <script src="views/js/evaluados_resultados_obreros.js"></script>
    <script src="views/js/logout.js"></script>
    <script src="views/js/GestionEvaluados.js"></script>
    <script src="views/js/evalAdministrativos.js"></script>
    <script src="views/js/evalObreros.js"></script>
    <script src="views/js/Datos_Evaluados.js"></script>
    <script src="views/js/GestionObjetivos.js"></script>
    <script src="views/js/GestionCompetencias.js"></script>
    <script src="views/js/cargarPerfil.js"></script>
    <script src="views/js/reportes.js"></script>
    <script src="views/js/reportes_administrativos.js"></script>
    <script src="views/js/reportes_obreros.js"></script>
    <script src="views/js/GestionIndicador.js"></script>
    <script src="views/js/grafica_comparativo.js"></script>
    <script src="views/js/graficas.js"></script>

    <script>
        var view = "";
    </script>
    
   
  </body>
</html>

  <!-- beautify ignore:end -->