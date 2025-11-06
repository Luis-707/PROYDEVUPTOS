<?php
include_once "../clases/Evaluados.php";

if (isset($dataCliente['_post']['otros_datos'])) {
    $dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];
}

$evaluado = new Evaluado($dataCliente['_post']);

$sql = $evaluado->sql_buscar_user_evaluado();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    $respuesta = $dataCliente['_post']['cedula_usuario'] . ' No Existe';
} else {
    // Tomar el id_usuario encontrado
    $idUsuario = (int)$respuesta[0][0]['id_usuario'];
    $evaluado->setIdUsuario($idUsuario);

    // 1) Eliminar permisos primero
    $sql = $evaluado->sql_eliminar_permiso();
    $this->ejecutarConsultaBdds($sql);

    // 2) Luego eliminar el usuario
    $sql = $evaluado->sql_eliminar_user_evaluado();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

return $respuesta;