<!doctype html>
<html lang="en">
    <head>
        <title>Detalles</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/vendor/linearicons/style.css">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
        <link rel="stylesheet" href="assets/css/demo.css">
        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
        <!-- ICONS -->
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">

        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/vendor/chartist/js/chartist.min.js"></script>

        <script src="assets/vendor/utils.js"></script>
        <script src="assets/scripts/klorofil-common.js"></script>
        <script>
            $(document).ready(function () {

                setAccordion();
                $.ajax({
                    type: 'POST',
                    url: 'PhpFiles/Functions.php',
                    data: 'cedula=' + sessionStorage.getItem('cedula') + '&functionName=buscar_todo',
                    success: function (data) {
                       var  result = JSON.parse(data);
                       console.log(result);
                        $("#proceso").append('<table class="table"><caption>Proceso</caption>' +
                                '<thead>' +
                                '<tr>' +
                                '<th><b>Numero</b></th><th>ACJ-' + result.numero + '</th></tr></thead><tbody><tr><td><b>Cliente</b></td>' +
                                '<td>' + result.nombre + '  ' + result.apellido + '</td></tr><tr><td><b>Cedula</b></td><td>' + result.cedula + '</td></tr><tr><td><b>Clase de Proceso</b></td>' +
                                '<td>' + result.tipoProceso + '</td></tr></tbody></table>');

                        if (result.fecha_recibe_docs !== undefined) {

                            llenarDemanda(result);
                        } else {
                            $("#cuerpoDemanda").append("NO existe la actuacion.");
                        }
                        if (result.fecha_sol_adelante !== undefined) {
                            llenarSentencia(result);
                        } else {
                            $("#cuerpoDemanda").append("NO existe la actuacion.");
                        }
                        
                        console.log('valor autoLiqui: ' + result.autoliqui.valor_aprobado);
                    }
                });
            });
            function llenarDemanda(result) {
                $("#cuerpoDemanda").append(
                        '<table class="table">' +
                        '<thead>' +
                        '<tr>' +
                        '<th><b>Fecha de recepcion de documentos</b></th><th>' + result.fecha_recibe_docs + '</th></tr></thead><tbody><tr><td><b>Fecha de elaboracion de demanda</b></td>' +
                        '<td>' + result.fecha_elab_demanda + '</td></tr><tr><td><b>Fecha de presentacion de demanda</b></td><td>' + result.fecha_presenta_demanda + '</td></tr><tr><td><b>Observaciones</b></td>' +
                        '<td>' + result.observaciones + '<tr><td><b>Estado</b></td>' +
                        '<td>' + result.estado_demanda + '</td></tr><tr><td><b>Fecha de admision</b></td><td>' + result.fecha_novedad + '</td></tr><tr><td><b>Juzgado</b></td>' +
                        '<td>' + result.juzgado + '</td></tr>' +
                        '<tr><td><b>Fecha Auto medida cautelar</b></td><td>' + result.fecha_medida_cautelar + '</td></tr>' +
                        '<tr><td><b>Fecha Auto mandamiento de pago</b></td><td>' + result.fecha_mandamiento_pago + '</td></tr>' +
                        '<tr><td><b>Observaciones Auto medida cautelar</b></td><td>' + result.observs_med_cautelar + '</td></tr>' +
                        '</tbody></table>'
                        );
            }
            function llenarSentencia(result) {

                $("#cuerpoSentencia").append(
                        '<table class="table">' +
                        '<thead>' +
                        '<tr>' +
                        '<th><b>Fecha de solicitud de seguir adelante</b></th><th>' + result.fecha_sol_adelante + '</th></tr></thead><tbody><tr><td><b>Observaciones</b></td>' +
                        '<td>' + result.observs_sol_adelante + '</td></tr><tr><td><b>Fecha Auto Seguir adelante</b></td><td>' + result.fecha_auto_adelante + '</td></tr><tr><td><b>Observaciones</b></td>' +
                        '<td>' + result.observaciones + '<tr><td><b>Estado</b></td>' +
                        '<td>' + result.observs_auto_adelante + '</td></tr><tr><td><b>Fecha Solicitud estado de endeudamiento</b></td><td>' + result.fecha_estado_endeuda + '</td></tr><tr><td><b>Observaciones</b></td>' +
                        '<td>' + result.observs_estado_endeuda + '</td></tr>' +
                        '</tbody></table>'
                        );

            }
            function llenarLiquidacion() {

            }

        </script>
    </head>
    <body>
        <!-- WRAPPER -->
        <div id="wrapper">
            <!-- NAVBAR -->
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="brand">
                    <a href="index.html"><img src="assets/img/logo-dark.png" alt="Klorofil Logo" class="img-responsive logo"></a>
                </div>
                <div class="container-fluid">
                    <div class="navbar-btn">
                        <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
                    </div>
                    <form class="navbar-form navbar-left">
                        <div class="input-group">
                            <input type="text" value="" class="form-control" placeholder="Buscar Proceso...">
                            <span class="input-group-btn"><button onclick="ver()" type="button" class="btn btn-primary">Buscar</button></span>
                        </div>
                    </form>

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
                        <h3 class="page-title">Detalles</h3>
                        <div class="row">
                            <section id="proceso">
                            </section>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="glyphicon glyphicon-minus" ></span>
                                                Demanda
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                        <div id="cuerpoDemanda" class="panel-body">

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <span class="glyphicon glyphicon-plus" ></span>
                                                Sentencia
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                        <div id="cuerpoSentencia" class="panel-body">

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <span class="glyphicon glyphicon-plus" ></span>
                                                Liquidacion
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                        <div id="cuerpoLiquidacion" class="panel-body">

                                        </div>
                                    </div> 
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                                                <span class="glyphicon glyphicon-plus" ></span>
                                                Cobros
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                        <div id="cuerpoCobros" class="panel-body">

                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <!-- END MAIN CONTENT -->
                    </div>
                    <!-- END MAIN -->
                    <div class="clearfix"></div>
                    <footer>
                        <div class="container-fluid">
                            <p class="copyright">&copy; 2017 <a href="https://www.themeineed.com" target="_blank">Theme I Need</a>. All Rights Reserved.</p>
                        </div>
                    </footer>
                </div>
            </div>
            <!-- END WRAPPER -->                
        </div>
    </body>
</html>
