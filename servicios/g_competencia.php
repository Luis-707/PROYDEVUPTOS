<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Evita que los errores se impriman en la salida
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/GestionCompetencia.php";

try {
    $competencia = new GestionCompetencia($dataCliente['_post'], $this->conexion);

    // Buscar si ya existe
    $sql = $competencia->sql_buscar_por_nombre_competencia();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {
        // No existe, insertar nuevo
        $sql = $competencia->sql_insertar_competencia();
        $this->ejecutarConsultaBdds($sql);

        echo json_encode([
            'success' => true,
            'message' => 'Competencia creado con éxito'
        ]);
    } else {
        // Ya existe
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe esta competencia'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;