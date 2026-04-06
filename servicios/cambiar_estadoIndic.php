<?php
include_once "../clases/GestionIndicador.php";

$data = $dataCliente['_post'];

// Validar que venga el indicador_id
/*if (empty($data['indicador_id'])) {
    return ['error' => 'El indicador_id es obligatorio'];
}

// Validar que venga el estado
if (empty($data['estado_indicador'])) {
    return ['error' => 'El estado_indicador es obligatorio'];
}*/

$editarEstadoIndicador = new GestionIndicador($dataCliente['_post']);

$sql = $editarEstadoIndicador->sql_buscar_indicardor_id();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
    $respuesta = $data['indicador_id'].' No Existe';
} else {
    $sql = $editarEstadoIndicador->sql_actualizar_estado_indicador();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data, 'l_indic');
return $respuesta;
