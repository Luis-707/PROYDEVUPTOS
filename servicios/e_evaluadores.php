<?php
include_once "../clases/CargosEvaluador.php";

// Asegúrate de que id_usuario viene correcto desde el frontend
// Si viene como otros_datos, conviértelo explícitamente
if (isset($dataCliente['_post']['otros_datos'])) {
    $dataCliente['_post']['id_usuario'] = $dataCliente['_post']['otros_datos'];
}

$cargo = new CargosEvaluador($dataCliente['_post']);

$sql = $cargo->sql_buscar_evaluadores();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
    $respuesta = $dataCliente['_post']['id_usuario'] . ' No Existe';
} else {
    $sql = $cargo->sql_eliminar_cargos();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

return $respuesta;