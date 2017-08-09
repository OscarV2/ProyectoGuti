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
        
        $cuenta = R::dispense('cuenta');
        $cuenta -> cuenta_numero = $data[6];
        $cuenta -> fecha_elab_cuenta = $data[7];        
        $cuenta -> cuenta_valor = $data[8];
        $cuenta -> conceptoCuenta = $data[9];
        $cuenta -> optionsCuentaPagada = $data[10];
        
        $citacion -> cuenta = $cuenta;
        $citacion -> demanda = $demanda;
        R::store($citacion);
        R::store($cuenta);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
    }
}

function guardarAviso($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso -> id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda -> id);
    
    if ($demanda != NULL && $citacion != NULL) {
        
        $aviso = R::dispense('aviso');
        $aviso -> entrega_aviso             = $data[0];
        $aviso -> guia                      = $data[1];
        $aviso -> fecha_inicio_noti         = $data[2];
        $aviso -> fecha_novedad             = $data[3];
        $aviso -> fecha_pre_juzgado         = $data[4];
        $aviso -> observs_fecha_pre_juzgado = $data[5];
        
        $cuenta = R::dispense('cuenta');
        $cuenta -> cuenta_numero            = $data[6];
        $cuenta -> fecha_elab_cuenta        = $data[7];        
        $cuenta -> cuenta_valor             = $data[8];
        $cuenta -> conceptoCuenta           = $data[9];
        $cuenta -> optionsCuentaPagada      = $data[10];
        $aviso -> entrega_aviso_deman       = $data[11];
        $aviso -> cuenta = $cuenta;
        $aviso -> demanda = $demanda;
        R::store($aviso);
        R::store($cuenta);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarEmplazamiento($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso -> id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda -> id);
    
    if ($demanda != NULL) {
        
        $emplazamiento = R::dispense('emplazamiento');
        $emplazamiento -> fecha_sol_juzgado      = $data[0];
        $emplazamiento -> fecha_aviso_ordena     = $data[1];
        $emplazamiento -> orden_emplazar         = $data[2];
        $emplazamiento -> medio_publicacion      = $data[3];
        $emplazamiento -> fecha_pub_edicto       = $data[4];
        $emplazamiento -> fecha_pre_juzgado      = $data[5];        
        $emplazamiento -> sol_inclusion          = $data[6];
        $emplazamiento -> fecha_sol_rne          = $data[7];
        $emplazamiento -> fecha_posesion_curador = $data[8];
               
        $emplazamiento -> sol_nom_curador        = $data[9];
        $emplazamiento -> contes_curador         = $data[10];
        
        $emplazamiento -> observaciones         = $data[11];
        
        $cuenta = R::dispense('cuenta');
        $cuenta -> cuenta_numero            = $data[12];
        $cuenta -> fecha_elab_cuenta        = $data[13];        
        $cuenta -> cuenta_valor             = $data[14];
        $cuenta -> conceptoCuenta           = $data[15];
        $cuenta -> optionsCuentaPagada      = $data[16];
        
        $emplazamiento -> cuenta = $cuenta;
        $emplazamiento -> demanda = $demanda;
        R::store($emplazamiento);
        R::store($cuenta);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarSentencia($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso -> id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda -> id);
    
    if ($demanda != NULL) {
        
        $sentencia = R::dispense('sentencia');
        $sentencia -> fecha_sol_adelante           = $data[0];
        $sentencia -> observs_sol_adelante         = $data[1];
        $sentencia -> fecha_auto_adelante          = $data[2];
        $sentencia -> observs_auto_adelante        = $data[3];
        $sentencia -> fecha_estado_endeuda         = $data[4];
        $sentencia -> observs_estado_endeudamiento = $data[5];        
     
        $cuenta = R::dispense('cuenta');
        $cuenta -> cuenta_numero            = $data[6];
        $cuenta -> fecha_elab_cuenta        = $data[7];        
        $cuenta -> cuenta_valor             = $data[8];
        $cuenta -> conceptoCuenta           = $data[9];
        $cuenta -> optionsCuentaPagada      = $data[10];
        
        $sentencia -> cuenta = $cuenta;
        $sentencia -> demanda = $demanda;
        R::store($sentencia);
        R::store($cuenta);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
       throw Exception();
    }
}

function guardarLiquidacion($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso -> id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda -> id);
    
    if ($demanda != NULL && $citacion != NULL) {
        
        $aviso = R::dispense('aviso');
        $aviso -> entrega_aviso             = $data[0];
        $aviso -> guia                      = $data[1];
        $aviso -> fecha_inicio_noti         = $data[2];
        $aviso -> fecha_novedad             = $data[3];
        $aviso -> fecha_pre_juzgado         = $data[4];
        $aviso -> observs_fecha_pre_juzgado = $data[5];        
        $aviso -> fecha_novedad             = $data[6];
        $aviso -> fecha_pre_juzgado         = $data[7];
        $aviso -> observs_fecha_pre_juzgado = $data[8];
               
        $aviso -> fecha_novedad             = $data[9];
        $aviso -> fecha_pre_juzgado         = $data[10];
        
        
        $cuenta = R::dispense('cuenta');
        $cuenta -> cuenta_numero            = $data[6];
        $cuenta -> fecha_elab_cuenta        = $data[7];        
        $cuenta -> cuenta_valor             = $data[8];
        $cuenta -> conceptoCuenta           = $data[9];
        $cuenta -> optionsCuentaPagada      = $data[10];
        $aviso -> entrega_aviso_deman       = $data[11];
        
        $aviso -> cuenta = $cuenta;
        $aviso -> demanda = $demanda;
        R::store($aviso);
        R::store($cuenta);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function desconectar() {
    R::close();
}
