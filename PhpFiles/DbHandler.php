<?php

//include 'ChromePhp.php';
require 'rb.php';
R::setup('mysql:host=localhost;'
        . 'dbname=gutidb', 'root', '');

function buscarUltimos(){
    
    $sql = 'SELECT * FROM proceso ORDER BY ID DESC LIMIT 10';
    $procesos = R::getAll($sql);
    echo 'numero de procesos '. count($procesos);
    for ($index = 0; $index < count($procesos); $index++) {
       
        $process = $procesos[$index];
        $id = $process['id'];
        
        //buscar cliente
        $cliente = R::findOne('cliente', 'proceso_id = ' . $id);
        echo '<tr>
                                                    <td>ACJ-'. $process['numero'] .'</td>
                                                    <td>'. $cliente['nombre'] . ' ' . $cliente['apellido'] .'</td>
                                                    <td>'. $cliente['cedula']   .'</td>
                                                    <td>'. $process['clase']  .'</td>
                                                    <td><p data-placement="top" data-toggle="tooltip" title="Editar"><button class="btn btn-primary btn-xs" data-title="Editar" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>
                                                    <td><p data-placement="top" data-toggle="tooltip" title="Ver"><button class="btn btn-danger btn-xs" data-title="Ver" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-zoom-in"></span></button></p></td>
                                                </tr>';
    }
    desconectar();
}

function buscarProcesoCedula($cedula) {

    $cliente = R::findOne('cliente', 'cedula = ' . $cedula);

    $proceso = R::load('proceso', $cliente['proceso_id']);

    $resultado[] = $proceso->numero;
    $resultado[] = $proceso->clase;
    $resultado[] = $cliente->nombre;
    $resultado[] = $cliente->apellido;
    $resultado[] = $cliente->cedula;
    $resultado[] = $proceso->id;
    desconectar();
    return $resultado;
}

function guardarElabDemanda($id, $data) {

    $proceso = R::load('proceso', $id);
    $demanda = R::dispense('demanda');

    $demanda->fecha_recibe_docs = $data[0];
    $demanda->fecha_elab_demanda = $data[1];
    $demanda->fecha_presenta_demanda = $data[2];
    $demanda->observacion = $data[3];

    $demanda -> proceso = $proceso;
   
     R::store($demanda);
     
    desconectar();
}

function guardarAdmiDemanda($id, $data) {
    
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso -> id);
   // $admiteDemanda = R::dispense('admiDemanda');
    if ($demanda != NULL) {
        
        $admiteDemanda = R::dispense('admidemanda');
        $admiteDemanda -> fecha_novedad = $data[0];
        $admiteDemanda -> fecha_medida_cautelar = $data[1];
        $admiteDemanda -> fecha_man_pago = $data[2];
        
        $admiteDemanda -> observs_novedad = $data[3];
        $admiteDemanda -> observs_med_cautelar = $data[4];
        $admiteDemanda -> observs_fecha_man_pago = $data[5];
        
        $admiteDemanda -> opcionesDemanda = $data[6];
        $admiteDemanda -> juzgado = $data[7];
        
        $admiteDemanda -> demanda = $demanda;
        
        R::store($admiteDemanda);
        desconectar();
    } else {
        
       ChromePhp::log('vdemanda es nula');
    }
}

function guardarCitacion($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso -> id);
    if ($demanda != NULL) {
        
        $citacion = R::dispense('citacion');
        $citacion -> entregaCitacion = $data[0];
        $citacion -> guia = $data[1];
        $citacion -> fecha_inicio_noti = $data[2];
        $citacion -> fecha_novedad = $data[3];
        $citacion -> fecha_pre_juzgado = $data[4];
        $citacion -> observs_fecha_pre_juzgado = $data[5];
        $citacion -> cuenta_numero = $data[6];
        $citacion -> fecha_elab_cuenta = $data[7];
        
        $citacion -> cuenta_valor = $data[8];
        $citacion -> conceptoCuenta = $data[9];
        $citacion -> optionsCuentaPagada = $data[10];
        
        $citacion -> demanda = $demanda;
        
        R::store($citacion);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
    }
}


/*
 * function ($cedula) {

  }
  function ($cedula) {

  }
 */

function desconectar() {
    R::close();
}
