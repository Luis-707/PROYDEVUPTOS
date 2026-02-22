<?php
include_once "../clases/EvaluacionesObreros.php";

$data = $dataCliente['_post'];

$eval = new EvaluacionesObreros($dataCliente['_post'], $this->conexion);

// Buscar si existe
$resp = $this->ejecutarConsultaBdds($eval->sql_buscar_evalObrero_id());

if (count($resp) == 0) {
    return $data['id_eval_obreros'] . ' No Existe';
}

// Actualizar
$this->ejecutarConsultaBdds($eval->sql_actualizar_periodo_obrero());

// Refrescar tabla
return $this->servicio($data, 'l_evalOb');
