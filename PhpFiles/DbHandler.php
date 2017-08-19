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
    
    if ($demanda != NULL) {
        
        $liquidacion = R::dispense('liquidacion');
        $liquidacion -> fecha_pre_juzgado         = $data[0];
        $liquidacion -> valor                     = $data[1];
        $liquidacion -> observaciones             = $data[2];
        
        $liquidacion -> proceso = $proceso;
        R::store($liquidacion);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarTrasladoCredito($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso -> id);
    
    if ($liquidacion != NULL) {
        
        $traslado = R::dispense('trasladocredito');
        $traslado -> fecha_traslado   = $data[0];
        $traslado -> observaciones    = $data[1];
                
        $traslado -> liquidacion = $liquidacion;
        R::store($traslado);
        
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarAutoLiqui($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso -> id);
    
    if ($liquidacion != NULL) {
        
        $autoLiqui = R::dispense('autoliqui');
        $autoLiqui -> fecha_auto_aprueba   = $data[0];
        $autoLiqui -> valor_aprobado       = $data[1];
        $autoLiqui -> opcionesApruebaLiqui = $data[2];
        $autoLiqui -> observaciones        = $data[3];
        
        $autoLiqui -> liquidacion = $liquidacion;
        R::store($autoLiqui);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarEmbargo($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso -> id);
    
    if ($liquidacion != NULL) {
        
        $embargo = R::dispense('embargo');
        $embargo -> fecha_inscripcion   = $data[0];
        $embargo -> observaciones    = $data[1];
                
        $embargo -> liquidacion = $liquidacion;
        R::store($embargo);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarDiliSecuestro($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso -> id);
    
    if ($liquidacion != NULL) {
        
        $diliSecuestro = R::dispense('dilisecuestro');
        $diliSecuestro -> fecha_solicitud        = $data[0];
        $diliSecuestro -> fecha_auto_diligencia  = $data[1];
        $diliSecuestro -> despacho_comisorio     = $data[2];
        $diliSecuestro -> comisionanA            = $data[3];
                
        $diliSecuestro -> liquidacion = $liquidacion;
        R::store($diliSecuestro);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function guardarAvaluo($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso -> id);
    
    if ($liquidacion != NULL) {
        
        $avaluo = R::dispense('avaluo');
        $avaluo -> fecha_sol_avaluo   = $data[0];
        $avaluo -> perito   = $data[1];
        $avaluo -> cedula   = $data[2];
        $avaluo -> fecha_aprobacion_avaluo = $data[3];
        $avaluo -> juzgado  = $data[4];
      
        $avaluo -> observaciones    = $data[5];
                
        $avaluo -> liquidacion = $liquidacion;
        R::store($avaluo);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}
function guardarRemate($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso -> id);
    
    if ($liquidacion != NULL) {
        
        $remate = R::dispense('remate');
        $remate -> fecha_traslado    = $data[0];
        $remate -> fecha_auto_remate = $data[1];
        $remate -> observaciones     = $data[2];
                
        $remate -> liquidacion = $liquidacion;
        R::store($remate);
        desconectar();
    } else {        
       ChromePhp::log('vdemanda es nula');
     //  throw Exception();
    }
}

function detalles($cedula){
    
    $cliente = R::findOne('cliente',  'cedula = ' . $cedula);
    $proceso = R::load('proceso',  $cliente['proceso_id']);
    $demanda = R::findOne('demanda',  'proceso_id = ' . $proceso -> id);
    
    $resultado = array('nombre' => $cliente -> nombre, 'apellido' => $cliente -> apellido, 
        'cedula'  => $cliente -> cedula , 'direccion' => $cliente ->direccion, 'tipoProceso' => $proceso ->clase,
        'numero' => $proceso ->numero,  'fecha_recibe_docs' => $demanda -> fecha_recibe_docs, 
        'fecha_elab_demanda' => $demanda -> fecha_elab_demanda,
        'fecha_presenta_demanda' => $demanda -> fecha_presenta_demanda, 'observaciones' => $demanda -> observacion);
    
    if ($demanda  === NULL) {
        
        $demanda = 'No existe actuacion';
    } 
    else {
        $admiteDemanda  =  R::findOne('admidemanda',  'demanda_id = ' . $demanda -> id);
        $resultado['fecha_novedad'] = $admiteDemanda ->fecha_novedad ;
        $resultado['fecha_medida_cautelar'] = $admiteDemanda -> fecha_medida_cautelar ;
        $resultado['fecha_mandamiento_pago'] = $admiteDemanda -> fecha_man_pago;
        $resultado['observs_med_cautelar'] = $admiteDemanda ->observs_med_cautelar ;
        $resultado['observs_fecha_man_pago'] = $admiteDemanda -> observs_fecha_man_pago;
        $resultado['estado_demanda'] = $admiteDemanda -> opciones_demanda;
        $resultado['juzgado'] = $admiteDemanda -> juzgado;
    }
   
    desconectar();
    return $resultado;
}

function desconectar() {
    R::close();
}