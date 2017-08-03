<?php
require 'DbHandler.php';
include 'ChromePhp.php';

$functionName = filter_input(INPUT_POST, 'functionName');
if ($functionName === 'buscarProceso') {
    
    
    $cedula = filter_input(INPUT_POST, 'cedula');
    try {
        $resultado = buscarProcesoCedula($cedula);
    } catch (Exception $exc) {
         ChromePhp::log($exc->getTraceAsString());
    }

    
    $tabla = '<table class="table"><caption>Proceso</caption>' .
    '<thead>' .
      '<tr>' .
        '<th><b>Numero</b></th><th>ACJ-' . $resultado[0] . '</th></tr></thead><tbody><tr><td><b>Cliente</b></td>' .
        '<td>' . $resultado[2] .' ' . $resultado[3] .'</td></tr><tr><td><b>Cedula</b></td><td>' . $resultado[4] . '</td></tr><tr><td><b>Clase de Proceso</b></td>' .
        '<td>' . $resultado[1] . '</td></tr></tbody></table>';
    
    $response['id'] = 'holaaaa';
    $response = array('tabla' => $tabla,  'id' => $resultado[5]);
    echo json_encode($response);
}

 elseif ($functionName === 'guardar-elab-demanda') {
     
    try {
        $id = filter_input(INPUT_POST, 'id');
        $data[] = filter_input(INPUT_POST, 'fecha_recibe_documentos');
        $data[] = filter_input(INPUT_POST, 'fecha_elab_demanda');
        $data[] = filter_input(INPUT_POST, 'fecha_presenta_dmanda');
         
         
         $data[] = filter_input(INPUT_POST, 'observs_elab_demanda');
        guardarElabDemanda($id, $data);
        echo 'success';
    } catch (Exception $exc) {
        echo 'Error';
    }

}

elseif ($functionName === 'guardar-admi-demanda') {
     
    try {
        $id = filter_input(INPUT_POST, 'id');
        $data[] = filter_input(INPUT_POST, 'fecha_novedad');
        $data[] = filter_input(INPUT_POST, 'fecha_medida_cautelar');
        $data[] = filter_input(INPUT_POST, 'fecha_man_pago');  
        
        $data[] = filter_input(INPUT_POST, 'observs__novedad');
        $data[] = filter_input(INPUT_POST, 'observs_med_cautelar');
        $data[] = filter_input(INPUT_POST, 'observs_fecha_man_pago');
        
        $data[] = filter_input(INPUT_POST, 'opcionesDemanda');
        $data[] = filter_input(INPUT_POST, 'juzgado');
        
        guardarAdmiDemanda($id, $data);
        echo 'success';
    } catch (Exception $exc) {
        ChromePhp::log('excepcion '.$exc->getTraceAsString());
        echo 'Error';
    }
    
    try {
        
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }


}

elseif ($functionName === 'guardar-admi-demanda') {
     
    try {
        $id = filter_input(INPUT_POST, 'id');
        $data[] = filter_input(INPUT_POST, 'optionsEntregaCitacion');
        $data[] = filter_input(INPUT_POST, 'guia');
        $data[] = filter_input(INPUT_POST, 'fecha_inicio_noti');  
        
        $data[] = filter_input(INPUT_POST, 'fecha_novedad');
        $data[] = filter_input(INPUT_POST, 'fecha_pre_juzgado');
        $data[] = filter_input(INPUT_POST, 'observs_fecha_pre_juzgado');
        
        $data[] = filter_input(INPUT_POST, 'cuenta_numero');
        $data[] = filter_input(INPUT_POST, 'fecha_elab_cuenta');
        $data[] = filter_input(INPUT_POST, 'cuenta_valor');
        
        $data[] = filter_input(INPUT_POST, 'conceptoCuenta');
        $data[] = filter_input(INPUT_POST, 'optionsCuentaPagada');
        
        guardarAdmiDemanda($id, $data);
        echo 'success';
    } catch (Exception $exc) {
        ChromePhp::log('excepcion '.$exc->getTraceAsString());
        echo 'Error';
    }
}


/*
 * elseif ($functionName === 'guardar-admi-demanda') {
     
    try {
        $id = filter_input(INPUT_POST, 'id');
        $data[] = filter_input(INPUT_POST, 'optionsEntregaCitacion');
        $data[] = filter_input(INPUT_POST, 'guia');
        $data[] = filter_input(INPUT_POST, '');  
        
        $data[] = filter_input(INPUT_POST, '');
        $data[] = filter_input(INPUT_POST, '');
        $data[] = filter_input(INPUT_POST, '');
        
        $data[] = filter_input(INPUT_POST, '');
        $data[] = filter_input(INPUT_POST, '');
        
        guardarAdmiDemanda($id, $data);
        echo 'success';
    } catch (Exception $exc) {
        ChromePhp::log('excepcion '.$exc->getTraceAsString());
        echo 'Error';
    }
}
 */