<?php
include_once "../clases/DatosEvaluados.php";

if (isset($dataCliente['_post']['otros_datos'])) {
    $dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];
}

$evaluados = new DatosEvaluados($dataCliente['_post']);

$sql = $evaluados->sql_buscar_evaluados();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    $respuesta = $dataCliente['_post']['cedula_usuario'] . ' No Existe';
} else {
    // Tomar el id_usuario encontrado
    $idUsuario = (int)$respuesta[0][0]['id_usuario'];
    $evaluados->setIdUsuario($idUsuario);

    // 2) Luego eliminar el usuario
    $sql = $evaluados->sql_eliminar_datos_evaluados();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

return $respuesta;