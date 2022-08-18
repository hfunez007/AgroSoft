<?php
if (!isset($_SESSION)) {
    session_start();
}
//   define('MAX_IDLE_TIME', 3);   
include("blockade.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Administrativo</title>

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="assets/DataTables/buttons.bootstrap5.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/DataTables/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/DataTables/responsive.bootstrap5.min.css" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.css">

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">

    <link rel="shortcut icon" href="assets/images/AgroFavicon1.ico" type="image/x-icon">

    <script src="assets/sweetalert2.min.js"></script>
    <link href="assets/sweetalert2.min.css" rel="stylesheet">

    <script type="text/javascript">
        localStorage.clear();
    </script>

    <!-- <style type="text/css">
        #temporizador {
            font-size: 12pt;
            /* margin: 70px auto;
            width: 100px; */
        }
    </style> -->
</head>

<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-menu">
                    <ul class="menu">
                        <!-- <div id="temporizador" class="row justify-content-center">
                            10
                        </div> -->
                        <li class='sidebar-title'>Menu</li>
                        <li class="sidebar-item active ">
                            <a href="site.php" class='sidebar-link'> <i data-feather="home" width="20"></i><span>Inicio</span></a>
                        </li>

                        <!-- Llamar menu -->
                        <?php
                        include "Menu.php";
                        ?>
                        <li class="sidebar-item active ">
                            <a href="https://www.portal-asihn.com" class='sidebar-link'> <i data-feather="git-pull-request" width="20"></i><span>A.S.I</span></a>
                        </li>
                    </ul>
                </div>

                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
                        <li class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="avatar avatar-md me-1">
                                    <img src="assets/images/avatar/avatar_hombre.png" alt="" srcset="">
                                    <!-- <img src="assets/images/avatar/avatar_hombre2.png" alt="" srcset=""> -->
                                    <span class="avatar-status bg-success"></span>
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">
                                    <?php
                                    include "datos/conexion_mysql.php";
                                    $cod_user = $_SESSION['codusu'];
                                    $query = "SELECT usu_alias FROM t_usuarios where usu_id = $cod_user";
                                    $consulta = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_array($consulta)) {
                                        $alias = $row[0];
                                    }
                                    echo 'Hola, ' . $alias;

                                    mysqli_close($conn);
                                    ?>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- <a class="dropdown-item" href="#"><i data-feather="user"></i> Account</a>
                                <a class="dropdown-item active" href="#"><i data-feather="mail"></i> Messages</a>
                                <a class="dropdown-item" href="#"><i data-feather="settings"></i> Settings</a> -->
                                <!-- <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="logout.php"><i data-feather="log-out"></i> Cerrar Sesion</a>
                            </div>
                        </li>
                        <li>
                            <img src="assets/images/logoasi.svg" width="200" height="50">
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="main-content container-fluid ">
                <div class="page-title ">
                    <h4>AGRO COMERCIAL TRES POTRILLOS</h4>
                    <!-- <p class="text-subtitle text-muted ">Papelería Honduras S. de R.L.</p> -->
                </div>

                <!-- Div de las views -->
                <section class="section">
                    <div id="pages"> </div>
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>Fecha: <?php date_default_timezone_set('America/Tegucigalpa');
                                    echo date('Y-m-d'); ?></p>
                    </div>
                    <div class="float-end">
                        <p>2021 &copy; <a href="https://www.portal-asihn.com" class="external" target="_blank">A.S.I.</a> - Todos los Derechos Reservados.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- <script src="views/APPODONTOGRAMA/public/js/jquery-2.0.3.min.js"></script> -->

    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather-icons/feather.min.js"></script>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/vendors/chartjs/Chart.min.js"></script>
    <script src="assets/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="assets/js/main.js"></script>


    <!-- MomentJS -->
    <script type='text/javascript' src='assets/js/momentjs/moment.js'></script>
    <script>
        moment().format();
    </script>

    <!-- Data Tables -->
    <script src="assets/DataTables/jquery.dataTables.min.js"></script>
    <script src="assets/DataTables/dataTables.bootstrap5.min.js"></script>
    <script src="assets/DataTables/dataTables.buttons.min.js"></script>
    <script src="assets/DataTables/buttons.bootstrap5.min.js"></script>
    <script src="assets/DataTables/jszip.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script src="assets/DataTables/buttons.html5.min.js"></script>
    <script src="assets/DataTables/buttons.print.min.js"></script>
    <script src="assets/DataTables/buttons.colVis.min.js"></script>
    <script src="assets/DataTables/dataTables.responsive.min.js"></script>
    <!-- <script src="assets/DataTables/responsive.bootstrap5.min.js"></script> -->


    <!-- <script>
        function Temporizador(id, inicio, final) {
            this.id = id;
            this.inicio = inicio;
            this.final = final;
            this.contador = this.inicio;

            this.conteoSegundos = function() {
                if (this.contador == this.final) {
                    this.conteoSegundos = null;
                    location.reload();
                    return;
                }

                document.getElementById(this.id).innerHTML = "Tiempo Para Actualizar: " + secondsToString(this.contador--);
                setTimeout(this.conteoSegundos.bind(this), 1000);
            };
        }

        let temporizador = new Temporizador('temporizador', 1800, 0);
        temporizador.conteoSegundos();


        function secondsToString(seconds) {
            var hour = Math.floor(seconds / 3600);
            hour = (hour < 10) ? '0' + hour : hour;
            var minute = Math.floor((seconds / 60) % 60);
            minute = (minute < 10) ? '0' + minute : minute;
            var second = seconds % 60;
            second = (second < 10) ? '0' + second : second;
            return hour + ':' + minute + ':' + second;
        }
    </script> -->


    <!-- <script src="/views/APPODONTOGRAMA/public/js/jsAcciones.js"></script> -->

    <script>
        $(document).ready(function() {
            /////// SISTEMA 
            $("#pages").load('views/view_index.php');
            $("#view_calendar").click(function() {
                url = "views/view_calendar.php";
                window.open(url);
            });

            $("#view_clientes").click(function() {
                $("#pages").load('views/view_clientes.php');
            });

            $("#view_medidas").click(function() {
                $("#pages").load('views/view_medidas.php');
            });

            $("#view_productos").click(function() {
                $("#pages").load('views/view_productos.php');
            });

            $("#view_marcas").click(function() {
                $("#pages").load('views/view_marcas.php');
            });

            $("#view_ingresos").click(function() {
                $("#pages").load('views/view_ingresos.php');
            });

            $("#view_empresas").click(function() {
                $("#pages").load('views/view_empresas.php');
            });

            $("#view_facturas").click(function() {
                $("#pages").load('views/view_facturas.php');
            });



            // Creadas
            $("#view_cotizacion0").click(function() {
                $("#pages").load("views/view_cotizacion.php?tipo=0");
            });
            // Revision
            $("#view_cotizacion1").click(function() {
                $("#pages").load('views/view_cotizacion.php?tipo=1');
            });
            // Aprobadas
            $("#view_cotizacion2").click(function() {
                $("#pages").load('views/view_cotizacion.php?tipo=2');
            });



            ////////////   MENU ADMINISTRACION  ///////////////// 
            $("#view_usuarios").click(function() {
                $("#pages").load('views/view_user.php');
            });
            $("#view_perfiles").click(function() {
                $("#pages").load('views/view_perfiles.php');
            });
        });
    </script>
</body>

</html>