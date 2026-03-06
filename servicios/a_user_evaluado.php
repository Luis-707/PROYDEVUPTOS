<?php

include_once "../clases/Evaluado.php";

$data = $dataCliente['_post'];
$evaluado = new Evaluado($data, $this->conexion);

// Buscar evaluado
$sql = $evaluado->sql_buscar();
$existe = $this->ejecutarConsultaBdds($sql);

if (count($existe) == 0) {
    echo json_encode([
        'success' => false,
        'message' => $data['cedula_usuario'] . ' no existe',
        'data' => []
    ]);
    exit;
}

// Actualizar evaluado
$sql = $evaluado->sql_actualizar();
$this->ejecutarConsultaBdds($sql);

// Devolver listado actualizado
$listado = $this->servicio($data, 'l_user_evaluado');

echo json_encode([
    'success' => true,
    'message' => 'Evaluado actualizado con éxito',
    'data' => $listado['data'] ?? $listado
]);
exit;