<?php

include_once "../clases/Evaluado.php";

$data = $dataCliente['_post'];

// Crear objeto Evaluado pasando datos y conexión $this->conexion (asumiendo existe)
$evaluado = new Evaluado($data, $this->conexion);

// Generar consulta para buscar usuario evaluado por cédula
$sql = $evaluado->sql_buscar_user_evaluado();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    // No existe el usuario evaluado
    $respuesta = $data['cedula_usuario'] . ' No Existe';
} else {
    // Existe, actualizar clave o datos que quieras actualizar
    $sql = $evaluado->sql_actualizar_user_evaluado();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

// Llamar a servicio con datos
$respuesta = $this->servicio($data, 'l_user_evaluado'); // asumiendo archivo l_usuario.php

return $respuesta;
