<?php
include_once "../clases/CargosSupervisor.php";

// Asegúrate de que id_usuario viene correcto desde el frontend
// Si viene como otros_datos, conviértelo explícitamente
if (isset($dataCliente['_post']['otros_datos'])) {
    $dataCliente['_post']['id_usuario'] = $dataCliente['_post']['otros_datos'];
}

$cargo = new CargosSupervisor($dataCliente['_post']);

$sql = $cargo->sql_buscar_supervisores();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
    $respuesta = $dataCliente['_post']['id_usuario'] . ' No Existe';
} else {
    $sql = $cargo->sql_eliminar_cargos_supervisores();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

return $respuesta;