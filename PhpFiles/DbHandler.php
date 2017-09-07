<?php

//include 'ChromePhp.php';
require 'rb.php';
R::setup('mysql:host=mysql://mysql:3306/;'
        . 'dbname=gutidb', 'root', 'vyhOxvCdPrfcJtEy');

function buscarUltimos() {

    $sql = 'SELECT * FROM proceso ORDER BY ID DESC LIMIT 10';
    $procesos = R::getAll($sql);
    echo 'numero de procesos ' . count($procesos);
    for ($index = 0; $index < count($procesos); $index++) {

        $process = $procesos[$index];
        $id = $process['id'];

        //buscar cliente
        $cliente = R::findOne('cliente', 'proceso_id = ' . $id);
        echo '<tr>
                                                    <td>ACJ-' . $process['numero'] . '</td>
                                                    <td>' . $cliente['nombre'] . ' ' . $cliente['apellido'] . '</td>
                                                    <td>' . $cliente['cedula'] . '</td>
                                                    <td>' . $process['clase'] . '</td>
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

    $demanda->proceso = $proceso;

    R::store($demanda);

    desconectar();
}

function guardarAdmiDemanda($id, $data) {

    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);
    // $admiteDemanda = R::dispense('admiDemanda');
    if ($demanda != NULL) {

        $admiteDemanda = R::dispense('admidemanda');
        $admiteDemanda->fecha_novedad = $data[0];
        $admiteDemanda->fecha_medida_cautelar = $data[1];
        $admiteDemanda->fecha_man_pago = $data[2];

        $admiteDemanda->observs_novedad = $data[3];
        $admiteDemanda->observs_med_cautelar = $data[4];
        $admiteDemanda->observs_fecha_man_pago = $data[5];

        $admiteDemanda->opcionesDemanda = $data[6];
        $admiteDemanda->juzgado = $data[7];

        $admiteDemanda->demanda = $demanda;

        R::store($admiteDemanda);
        desconectar();
    } else {

        ChromePhp::log('vdemanda es nula');
    }
}

function guardarCitacion($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);
    if ($demanda != NULL) {

        $citacion = R::dispense('citacion');
        $citacion->entregaCitacion = $data[0];
        $citacion->guia = $data[1];
        $citacion->fecha_inicio_noti = $data[2];
        $citacion->fecha_novedad = $data[3];
        $citacion->fecha_pre_juzgado = $data[4];
        $citacion->observs_fecha_pre_juzgado = $data[5];

        $cuenta = R::dispense('cuenta');
        $cuenta->cuenta_numero = $data[6];
        $cuenta->fecha_elab_cuenta = $data[7];
        $cuenta->cuenta_valor = $data[8];
        $cuenta->conceptoCuenta = $data[9];
        $cuenta->optionsCuentaPagada = $data[10];

        $proceso->ownCuentaList[] = $cuenta;
        $citacion->demanda = $demanda;
        R::store($citacion);
        R::store($cuenta);
        R::store($proceso);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
    }
}

function guardarAviso($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda->id);

    if ($demanda != NULL && $citacion != NULL) {

        $aviso = R::dispense('aviso');
        $aviso->entrega_aviso = $data[0];
        $aviso->guia = $data[1];
        $aviso->fecha_inicio_noti = $data[2];
        $aviso->fecha_novedad = $data[3];
        $aviso->fecha_pre_juzgado = $data[4];
        $aviso->observs_fecha_pre_juzgado = $data[5];

        $cuenta = R::dispense('cuenta');
        $cuenta->cuenta_numero = $data[6];
        $cuenta->fecha_elab_cuenta = $data[7];
        $cuenta->cuenta_valor = $data[8];
        $cuenta->conceptoCuenta = $data[9];
        $cuenta->optionsCuentaPagada = $data[10];
        $aviso->entrega_aviso_deman = $data[11];
        $proceso->ownCuentaList[] = $cuenta;
        $aviso->demanda = $demanda;
        R::store($aviso);
        R::store($cuenta);
        R::store($proceso);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarEmplazamiento($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda->id);

    if ($demanda != NULL) {

        $emplazamiento = R::dispense('emplazamiento');
        $emplazamiento->fecha_sol_juzgado = $data[0];
        $emplazamiento->fecha_aviso_ordena = $data[1];
        $emplazamiento->orden_emplazar = $data[2];
        $emplazamiento->medio_publicacion = $data[3];
        $emplazamiento->fecha_pub_edicto = $data[4];
        $emplazamiento->fecha_pre_juzgado = $data[5];
        $emplazamiento->sol_inclusion = $data[6];
        $emplazamiento->fecha_sol_rne = $data[7];
        $emplazamiento->fecha_posesion_curador = $data[8];

        $emplazamiento->sol_nom_curador = $data[9];
        $emplazamiento->contes_curador = $data[10];

        $emplazamiento->observaciones = $data[11];

        $cuenta = R::dispense('cuenta');
        $cuenta->cuenta_numero = $data[12];
        $cuenta->fecha_elab_cuenta = $data[13];
        $cuenta->cuenta_valor = $data[14];
        $cuenta->conceptoCuenta = $data[15];
        $cuenta->optionsCuentaPagada = $data[16];

        $proceso->ownCuentaList[] = $cuenta;
        $emplazamiento->demanda = $demanda;
        R::store($emplazamiento);
        R::store($cuenta);
        R::store($proceso);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarSentencia($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);
    $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda->id);

    if ($demanda != NULL) {

        $sentencia = R::dispense('sentencia');
        $sentencia->fecha_sol_adelante = $data[0];
        $sentencia->observs_sol_adelante = $data[1];
        $sentencia->fecha_auto_adelante = $data[2];
        $sentencia->observs_auto_adelante = $data[3];
        $sentencia->fecha_estado_endeuda = $data[4];
        $sentencia->observs_estado_endeudamiento = $data[5];

        $cuenta = R::dispense('cuenta');
        $cuenta->cuenta_numero = $data[6];
        $cuenta->fecha_elab_cuenta = $data[7];
        $cuenta->cuenta_valor = $data[8];
        $cuenta->conceptoCuenta = $data[9];
        $cuenta->optionsCuentaPagada = $data[10];

        $proceso->ownCuentaList[] = $cuenta;
        $sentencia->demanda = $demanda;
        R::store($sentencia);
        R::store($cuenta);
        R::store($proceso);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        throw Exception();
    }
}

function guardarLiquidacion($id, $data) {
    $proceso = R::load('proceso', $id);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);

    if ($demanda != NULL) {

        $liquidacion = R::dispense('liquidacion');
        $liquidacion->fecha_pre_juzgado = $data[0];
        $liquidacion->valor = $data[1];
        $liquidacion->observaciones = $data[2];

        $liquidacion->proceso = $proceso;
        R::store($liquidacion);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarTrasladoCredito($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);

    if ($liquidacion != NULL) {

        $traslado = R::dispense('trasladocredito');
        $traslado->fecha_traslado = $data[0];
        $traslado->observaciones = $data[1];

        $traslado->liquidacion = $liquidacion;
        R::store($traslado);

        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarAutoLiqui($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);

    if ($liquidacion != NULL) {

        $autoLiqui = R::dispense('autoliqui');
        $autoLiqui->fecha_auto_aprueba = $data[0];
        $autoLiqui->valor_aprobado = $data[1];
        $autoLiqui->opcionesApruebaLiqui = $data[2];
        $autoLiqui->observaciones = $data[3];

        $cuenta = R::dispense('cuenta');
        $cuenta->cuenta_numero = $data[4];
        $cuenta->fecha_elab_cuenta = $data[5];
        $cuenta->cuenta_valor = $data[6];
        $cuenta->conceptoCuenta = $data[7];
        $cuenta->optionsCuentaPagada = $data[8];

        $proceso->ownCuentaList[] = $cuenta;
        $autoLiqui->liquidacion = $liquidacion;
        R::store($autoLiqui);
        R::store($proceso);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarEmbargo($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);

    if ($liquidacion != NULL) {

        $embargo = R::dispense('embargo');
        $embargo->fecha_inscripcion = $data[0];
        $embargo->observaciones = $data[1];

        $embargo->liquidacion = $liquidacion;
        R::store($embargo);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarDiliSecuestro($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);

    if ($liquidacion != NULL) {

        $diliSecuestro = R::dispense('dilisecuestro');
        $diliSecuestro->fecha_solicitud = $data[0];
        $diliSecuestro->fecha_auto_diligencia = $data[1];
        $diliSecuestro->despacho_comisorio = $data[2];
        $diliSecuestro->comisionanA = $data[3];

        $cuenta = R::dispense('cuenta');
        $cuenta->cuenta_numero = $data[4];
        $cuenta->fecha_elab_cuenta = $data[5];
        $cuenta->cuenta_valor = $data[6];
        $cuenta->conceptoCuenta = $data[7];
        $cuenta->optionsCuentaPagada = $data[8];

        $proceso->ownCuentaList[] = $cuenta;
        $diliSecuestro->liquidacion = $liquidacion;
        R::store($diliSecuestro);
        R::store($proceso);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarAvaluo($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);

    if ($liquidacion != NULL) {

        $avaluo = R::dispense('avaluo');
        $avaluo->fecha_sol_avaluo = $data[0];
        $avaluo->perito = $data[1];
        $avaluo->cedula = $data[2];
        $avaluo->fecha_aprobacion_avaluo = $data[3];
        $avaluo->juzgado = $data[4];

        $avaluo->observaciones = $data[5];

        $avaluo->liquidacion = $liquidacion;
        R::store($avaluo);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function guardarRemate($id, $data) {
    $proceso = R::load('proceso', $id);
    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);

    if ($liquidacion != NULL) {

        $remate = R::dispense('remate');
        $remate->fecha_traslado = $data[0];
        $remate->fecha_auto_remate = $data[1];
        $remate->observaciones = $data[2];

        $remate->liquidacion = $liquidacion;
        R::store($remate);
        desconectar();
    } else {
        ChromePhp::log('vdemanda es nula');
        //  throw Exception();
    }
}

function detalles($cedula) {

    $cliente = R::findOne('cliente', 'cedula = ' . $cedula);
    $proceso = R::load('proceso', $cliente['proceso_id']);
    $demanda = R::findOne('demanda', 'proceso_id = ' . $proceso->id);
    
    $resultado = array('tipoProceso' => $proceso->clase,
        'numero' => $proceso->numero);
    
  $resultado['cuentas'] = getCuentas($proceso);
  $resultado['clientes'] = getClientes($proceso);
 /*   if ($demanda !== NULL) {

        $resultado['fecha_recibe_docs'] = $demanda -> fecha_recibe_docs;
        $resultado['fecha_elab_demanda'] = $demanda -> fecha_elab_demanda;
       $resultado['fecha_presenta_demanda'] = $demanda -> fecha_presenta_demanda;
       $resultado['observaciones'] = $demanda -> observaciones;
        $admiteDemanda = R::findOne('admidemanda', 'demanda_id = ' . $demanda->id);
        if ($admiteDemanda !== NULL) {

            $resultado['fecha_novedad'] = $admiteDemanda->fecha_novedad;
            $resultado['fecha_medida_cautelar'] = $admiteDemanda->fecha_medida_cautelar;
            $resultado['fecha_mandamiento_pago'] = $admiteDemanda->fecha_man_pago;
            $resultado['observs_med_cautelar'] = $admiteDemanda->observs_med_cautelar;
            $resultado['observs_fecha_man_pago'] = $admiteDemanda->observs_fecha_man_pago;
            $resultado['estado_demanda'] = $admiteDemanda->opciones_demanda;
            $resultado['juzgado'] = $admiteDemanda->juzgado;
        }

        $citacion = R::findOne('citacion', 'demanda_id = ' . $demanda->id);
        if ($citacion !== NULL) {

            $resultado['entrega_citacion'] = $citacion->entrega_citacion;
            $resultado['guia_citacion'] = $citacion->guia;
            $resultado['fecha_citacion'] = $citacion->fecha_novedad;
            $resultado['fecha_inicio_noti_citacion'] = $citacion->fecha_inicio_noti;
            $resultado['fecha_pre_juzgado'] = $citacion->fecha_pre_juzgado;
            $resultado['observs_fecha_pre_juzgado'] = $citacion->observs_fecha_pre_juzgado;
        }

        $aviso = R::findOne('aviso', 'demanda_id = ' . $demanda->id);
        if ($aviso !== NULL) {
            $resultado['entrega_aviso'] = $aviso->entrega_aviso;
            $resultado['guia_aviso'] = $aviso->guia;
            $resultado['fecha_inicio_noti_aviso'] = $aviso->fecha_inicio_noti;
            $resultado['fecha_aviso'] = $aviso->fecha_novedad;
            $resultado['fecha_pre_juzgado_aviso'] = $aviso->fecha_pre_juzgado;
            $resultado['observaciones_aviso'] = $aviso->observs_fecha_pre_juzgado;
            $resultado['entrega_aviso_deman'] = $aviso->entrega_aviso_deman;
        }

        $sentencia = R::findOne('sentencia', 'demanda_id = ' . $demanda->id);
        if ($sentencia !== NULL) {
            $resultado['fecha_sol_adelante'] = $sentencia->fecha_sol_adelante;
            $resultado['observs_sol_adelante'] = $sentencia->observs_sol_adelante;
            $resultado['fecha_auto_adelante'] = $sentencia->fecha_auto_adelante;
            $resultado['observs_auto_adelante'] = $sentencia->observs_auto_adelante;
            $resultado['fecha_estado_endeuda'] = $sentencia->fecha_estado_endeuda;
            $resultado['observs_estado_endeuda'] = $sentencia->observs_estado_endeudamiento;
        }
    }

    $liquidacion = R::findOne('liquidacion', 'proceso_id = ' . $proceso->id);
    $resultado['fecha_pre_juz_liqui'] = $liquidacion->fecha_pre_juzgado;
    $resultado['valor_liqui'] = $liquidacion->valor;
    $resultado['observaciones_liqui'] = $liquidacion->observaciones;

    $autoliqui = R::findOne('autoliqui', 'liquidacion_id = ' . $liquidacion->id);
      
    $avaluo = R::findOne('avaluo', 'liquidacion_id = ' . $liquidacion->id);

    $dilisecuestro = R::findOne('dilisecuestro', 'liquidacion_id = ' . $liquidacion->id);
    
    $embargo = R::findOne('embargo', 'liquidacion_id = ' . $liquidacion->id);
    $remate = R::findOne('remate', 'liquidacion_id = ' . $liquidacion->id);
    $trasladoCredito = R::findOne('trasladocredito', 'liquidacion_id = ' . $liquidacion->id);

   $resultado['autoliqui'] = getAutoLiquiArray($autoliqui); 
    $resultado['avaluo'] = getAvaluoArray($avaluo);
    
    $resultado['dilisecuestro'] = getDiliSecuestroArray($dilisecuestro);
    $resultado['embargo'] = getEmbargoArray($embargo);
    $resultado['remate'] = getRemateArray($remate);
    $resultado['trasladocredito'] = getTrasladoCreditoArray($trasladoCredito);
      
     
    */
    desconectar();
    return $resultado;
}

function getCuentas($proceso) {
   $contCuentas = 0;
    $cuentas = array();
    foreach ($proceso -> ownCuentaList as $cuenta ) {
      
        $arrayCuentas = array();
        $arrayCuentas['cuenta_numero'] = $cuenta -> cuenta_numero;    
        $arrayCuentas['fecha_elab_cuenta'] = $cuenta -> fecha_elab_cuenta; 
        $arrayCuentas['cuenta_valor'] = $cuenta -> cuenta_valor; 
        $arrayCuentas['concepto_cuenta'] = $cuenta -> concepto_cuenta; 
        $arrayCuentas['options_cuenta_pagada'] = $cuenta -> options_cuenta_pagada; 
        $cuentas[$contCuentas] = $arrayCuentas;
        $contCuentas++;
    }
    return $cuentas;
        
}
function getClientes($proceso) {
   $contClientes = 0;
    $clientes = array();
    foreach ($proceso -> ownClienteList as $cliente ) {
        
        $arrayClientes = array();
        $arrayClientes['nombre'] = $cliente -> nombre;    
        $arrayClientes['apellido'] = $cliente -> apellido; 
        $arrayClientes['cedula'] = $cliente -> cedula; 
        $arrayClientes['direccion'] = $cliente -> direccion; 
        ChromePhp::log('nombres '.$arrayClientes['nombre']);
        ChromePhp::log('cedulas '.$arrayClientes['cedula']);
        ChromePhp::log('apellidos '.$arrayClientes['apellido']);
        ChromePhp::log('direcciones '.$arrayClientes['direccion']);
        $clientes[$contClientes] = $arrayClientes;
        $contClientes++;
    }
    return $clientes;        
}
function getAutoLiquiArray($autoliqui) {
    
    return array('fecha_auto_aprueba' => $autoliqui -> fecha_auto_aprueba,
        'valor_aprobado' => $autoliqui -> valor_aprobado, 
        'opciones_aprueba_liqui' => $autoliqui -> opciones_aprueba_liqui,
        'observaciones' => $autoliqui -> observaciones);
}

function getAvaluoArray($avaluo) {
    
    return array('fecha_sol_avaluo' => $avaluo -> fecha_sol_avaluo,
        'perito' => $avaluo -> perito, 
        'cedula' => $avaluo -> cedula,
        'fecha_aprobacion_avaluo' => $avaluo -> fecha_aprobacion_avaluo,
        'observaciones' => $avaluo -> observaciones,
         'juzgado' => $avaluo -> juzgado);
}
function getDiliSecuestroArray($dilisecuestro) {
    
    return array('fecha_auto_diligencia' => $dilisecuestro -> fecha_auto_diligencia,
        'fecha_solicitud' => $dilisecuestro -> fecha_solicitud, 
        'despacho_comisorio' => $dilisecuestro -> despacho_comisorio,
        'comisionan_a' => $dilisecuestro ->comisionan_a);
}
function getEmbargoArray($embargo) {
    
    return array('fecha_inscripcion' => $embargo -> fecha_inscripcion,
        'observaciones' => $embargo -> observaciones );
}
function getRemateArray($remate) {
    
    return array('fecha_traslado' => $remate -> fecha_traslado,
        'fecha_auto_remate' => $remate -> fecha_auto_remate, 
        'observaciones' => $remate -> observaciones
        );
}
function getTrasladoCreditoArray($trasladoCredito) {
    
    return array('fecha_traslado' => $trasladoCredito -> fecha_traslado,
        'observaciones' => $trasladoCredito -> observaciones, );
}
function desconectar() {
    R::close();
}
