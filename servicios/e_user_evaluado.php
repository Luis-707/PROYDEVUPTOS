<?php
/*include_once "../clases/Evaluado.php";

// Mapear 'otros_datos' a 'cedula_usuario' en el array de datos
$dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];

// Crear instancia de Evaluado pasando datos y conexión
$evaluado = new Evaluado($dataCliente['_post']);

// Buscar el usuario evaluado
$sql = $evaluado->sql_buscar_user_evaluado();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    $respuesta = $dataCliente['_post']['login'] . ' No Existe';
} else {
    $sql = $evaluado->sql_eliminar_user_evaluado();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

return $respuesta;
*/
include_once "../clases/Evaluado.php";

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