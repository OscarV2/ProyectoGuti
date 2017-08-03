<?php
require 'rb.php';
R::setup('mysql:host=localhost;'
        . 'dbname=gutidb', 'root', '');

//obtener numero de proceso
$idProcesos = filter_input(INPUT_POST, 'numeroProceso1');
$numProceso = filter_input(INPUT_POST, 'numeroProceso2');
// obtener arrays nuevo(s) cliente(s)
$arrayNombres = filter_input(INPUT_POST, "nombreNuevoCliente", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arrayApellidos = filter_input(INPUT_POST, "apellidoNuevoCliente", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arrayCedula = filter_input(INPUT_POST, "cedulaNuevoCliente", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arrayDireccion = filter_input(INPUT_POST, "direccionNuevoCliente", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

//obtener arrays nuevo(s) cliente(s)
$arrayObligacion = filter_input(INPUT_POST, "numNuevaObligacion", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arrayPagare = filter_input(INPUT_POST, "pagareNuevaObligacion", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arrayValor = filter_input(INPUT_POST, "valorNuevaObligacion", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arrayCuantia = filter_input(INPUT_POST, "cuantiaNuevaObligacion", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

//obtener resto de datos
$claseProceso = filter_input(INPUT_POST, 'claseDeProceso');
$codigoOficina = filter_input(INPUT_POST, 'codigoOficina');
$directorOficina = filter_input(INPUT_POST, 'directorOficina');
$optionsCuantia = filter_input(INPUT_POST, 'optionsCuantia');

//nuevo proceso
$proceso = R::dispense('proceso');
//$cliente = R::dispense('cliente');     //  nuevo cliente
//$obligacion = R::dispense('obligacion');  //  nueva obligacion

$proceso->numero = $numProceso;
$proceso->clase = $claseProceso;
$proceso->codigoOficina = $codigoOficina;
$proceso->director = $directorOficina;
$proceso->cuantia = $optionsCuantia;

for ($index = 0; $index < count($arrayNombres); $index++) {

    $nuevoCliente = R::dispense('cliente');
    $nuevoCliente -> nombre = $arrayNombres[$index];
    $nuevoCliente -> cedula = $arrayCedula[$index];
    $nuevoCliente -> apellido = $arrayApellidos[$index];
    $nuevoCliente -> direccion = $arrayDireccion[$index];
    
    $listaClientes[] = $nuevoCliente;
        
/*
    echo 'nombres' . $arrayNombres[$index] . '<br>';
    $query2 = "INSERT INTO cliente(tipo, cedula, nombre, apellidos, direccion, proceso_numero) "
            . "VALUES('$tipoCliente' , '$arrayCedula[$index]' , '$arrayNombres[$index]' ,"
            . "'$arrayApellidos[$index]' , '$arrayDireccion[$index]' , '$numProceso')";

    $result2 = mysqli_query($conexion, $query2);
    if ($result2) {
        echo '<br> insertando clientes exitosas ';
    } else {

        echo 'error insertando clientes';
    }
 * */
 
}

for ($index = 0; $index < count($arrayObligacion); $index++) {

    $nuevaOb = R::dispense('obligacion');
    $nuevaOb -> numero = $arrayObligacion[$index];
    $nuevaOb -> pagare = $arrayPagare[$index];
    $nuevaOb -> valor = $arrayValor[$index];
    $nuevaOb -> cuantia = $arrayCuantia[$index];
    
    $listaObs[] = $nuevaOb; 
    /*
    $query3 = "INSERT INTO obligacion(numero, pagare, valor, cuantia, proceso_numero)"
            . " VALUES('$arrayObligacion[$index]' , '$arrayPagare[$index]' , '$arrayValor[$index]' ,"
            . " '$arrayCuantia[$index]' , '$numProceso')";

    $result3 = mysqli_query($conexion, $query3);
    if ($result3) {
        echo '<br> insertando obligaciones exitosas <br>';
    } else {

        echo '<br>error insertando obligacion';
    }*/
}
try {
    
    
    foreach ($listaClientes as $cliente){

        
        $proceso -> ownClienteList[] = $cliente;
         R::store($cliente);
    }
    foreach ($listaObs as $obligation){
        
        $proceso -> ownObligacionList[] = $obligation;
        R::store($obligation);
    }
    R::store($proceso);
   
    echo 'success';
} catch (Exception $exc) {
    echo 'false';
} 

R::close();
//mysqli_close($conexion);
