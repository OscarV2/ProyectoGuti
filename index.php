<!doctype html>
<html lang="en">
    <head>
        <title>Inicio</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/vendor/linearicons/style.css">
        <link rel="stylesheet" href="assets/vendor/chartist/css/chartist-custom.css">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
        <link rel="stylesheet" href="assets/css/demo.css">
        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
        <!-- ICONS -->
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/scripts/klorofil-common.js"></script>

        <script>
            //      $("#form_buscar_todo").submit(function (e) {

            //e.preventDefault();
            //  sessionStorage.setItem('cedula',  $("#in_cedula").val());
            //  window.location.replace("detalles.php");
            //  });
            function irDetalles() {
                sessionStorage.setItem('cedula', $("#in_cedula").val());
                window.location.replace("detalles.php");
                console.log('me fui');
            }
        </script>
    </head>
    <body>
        <!-- WRAPPER -->
        <div id="wrapper">
            <!-- NAVBAR -->
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="brand">
                    <a href="index.html"><img src="assets/img/logo-dark.png" alt="Logo" class="img-responsive logo"></a>
                </div>
                <div class="container-fluid">
                    <div class="navbar-btn">
                        <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
                    </div>
                    <div id="form_buscar_todo" class="navbar-form navbar-left">
                        <div class="input-group">
                            <input id="in_cedula" type="text" name="cedula" value="" class="form-control" placeholder="Buscar Proceso..." required>
                            <span class="input-group-btn"><button  onclick="irDetalles()" class="btn btn-primary">Buscar</button></span>
                        </div>
                    </div>
                    <div id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right">											
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/img/user.png" class="img-circle" alt="Avatar"> <span>Oscar</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#"><i class="lnr lnr-user"></i> <span>Mi Perfil</span></a></li>
                                    <li><a href="#"><i class="lnr lnr-cog"></i> <span>Configuracion</span></a></li>
                                    <li><a href="#"><i class="lnr lnr-exit"></i> <span>Cerrar Sesión</span></a></li>
                                </ul>
                            </li>						
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- END NAVBAR -->
            <!-- LEFT SIDEBAR -->
            <div id="sidebar-nav" class="sidebar">
                <div class="sidebar-scroll">
                    <nav>
                        <ul class="nav">
                            <li><a href="index.php" class="active"><i class="lnr lnr-home"></i> <span>Inicio</span></a></li>
                            <li><a href="nuevo.html" class=""><i class="lnr lnr-code"></i> <span>Nuevo Proceso</span></a></li>
                            <li>
                                <a href="#subPages" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>Demanda</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="elab-demanda.html" class="">Elaborar Demanda</a></li>
                                        <li><a href="admi-demanda.html" class="">Admisión demanda</a></li>
                                        <li>
                                            <a href="#subNotifications" data-toggle="collapse" class="collapsed">
                                                <i class="lnr lnr-file-empty"></i> 
                                                <span>Notificacion</span>
                                                <i class="icon-submenu lnr lnr-chevron-left"></i>
                                            </a>
                                            <div id="subNotifications" class="collapse">
                                                <ul class="nav">
                                                    <li><a href="citacion.html" class="">Elaborar Citacion</a></li>
                                                    <li><a href="aviso.html" class="">Elaborar Aviso</a></li>
                                                    <li><a href="emplazamiento.html" class="">Emplazamiento</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>       
                            <li><a href="sentencia.html" class=""><i class="lnr lnr-dice"></i> <span>Sentencia</span></a></li>
                            <li><a href="liquidacion.html" class=""><i class="lnr lnr-alarm"></i> <span>Liquidación</span></a></li>
                            <li><a href="otro.html" class=""><i class="lnr lnr-alarm"></i> <span>Otro</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- END LEFT SIDEBAR -->
            <!-- MAIN -->
            <div class="main">
                <!-- MAIN CONTENT -->
                <div class="main-content">
                    <div class="container-fluid">
                        <!-- OVERVIEW -->
                        <div class="panel panel-headline">
                            <div class="panel-heading">
                                <h3 class="panel-title">Todos los procesos</h3>
                            </div>
                            <div id="wrapper2" class="panel-body">
                                <div class="row">
                                    <!-- BASIC TABLE -->
                                    <div class="panel">
                                        <table class="table">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Numero de proceso</th>
                                                    <th>Cliente</th>
                                                    <th>Cedula</th>
                                                    <th>Tipo</th>
                                                    <th>Editar</th>
                                                    <th>Ver</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include 'PhpFiles/DbHandler.php';
                                                include 'ChromePhp.php';
                                                $servername = "mysql://mysql:3306/";
                                                $username = "root";
                                                $password = "vyhOxvCdPrfcJtEy";

// Create connection
                                                $conn = mysqli_connect($servername, $username, $password);
ChromePhp::log('cambio en php.index');
// Check connection
                                                if (!$conn) {
                                                    die("Connection failed: " . mysqli_connect_error());
                                                    ChromePhp::log('no se pudo conectar a la bd');
                                                }
                                                echo "Connected successfully";
                                                buscarUltimos();
                                                ?>                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- END BASIC TABLE -->
                                </div>
                            </div>
                        </div>
                        <!-- END OVERVIEW -->										
                    </div>
                </div>
                <!-- END MAIN CONTENT -->
            </div>
            <!-- END MAIN -->
            <div class="clearfix"></div>
            <footer>
                <div class="container-fluid">
                    <p class="copyright">&copy; 2017 <a href="https://www.themeineed.com" target="_blank">Theme I Need</a>. Todos los derechos reservados.</p>
                </div>
            </footer>
        </div>
        <!-- END WRAPPER -->        
    </body>
</html>
