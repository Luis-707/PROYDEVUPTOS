<?php
include_once "../clases/EvaluacionAdministrativos.php";

$data = $dataCliente['_post'];

// Crear instancia de la clase EvaluacionesAdministrativos con los datos recibidos
$editarEstadoEval = new EvaluacionesAdministrativos($dataCliente['_post']);

// Buscar la evaluación por ID
$sql = $editarEstadoEval->sql_buscar_evalAdmin_id();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    $respuesta = $dataCliente['_post']['id_eval_admin']. ' No Existe';
} else {
    // Actualizar el estado de la evaluación
    $sql = $editarEstadoEval->sql_actualizar_estado_evalAdmin();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_evalAdministrativos'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
// Retornar la respuesta
return $respuesta;
