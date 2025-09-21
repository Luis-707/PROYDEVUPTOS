<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Asignar_Supervisores.php";
include_once "../controlador/config/configuracion.php";
include_once "../controlador/BDD.php";

try {
    // Validar datos de entrada
    if (!isset($dataCliente['_post']) || empty($dataCliente['_post'])) {
        echo json_encode([
            "success" => false,
            "message" => "No se recibieron datos para la asignación"
        ]);
        exit;
    }

    // Instanciar clase
    $asignacion = new AsignarSupervisores($dataCliente['_post'], $this->conexion);

    $insertados = 0;
    $existentes = 0;

    foreach ($asignacion->getEvaluadores() as $idEval) {
        if (empty($idEval) || !is_numeric($idEval)) {
            continue; // ignorar valores inválidos
        }

        // Buscar si ya existe la asignación
        $sqlBuscar = $asignacion->sql_buscar_asignacion($idEval);
        if (empty($sqlBuscar)) {
            continue;
        }

        $respuesta = $this->ejecutarConsultaBdds($sqlBuscar);

        if ($respuesta === false) {
            continue; // error en consulta
        }

        // Si no existe, insertar
        if (count($respuesta) == 0) {
            $sqlInsert = $asignacion->sql_guardar_asignacion($idEval);
            if (empty($sqlInsert)) {
                continue;
            }

            $resInsert = $this->ejecutarConsultaBdds($sqlInsert);

            if ($resInsert !== false) {
                $insertados++;
            }
        } else {
            $existentes++;
        }
    }

    echo json_encode([
        "success" => true,
        "message" => "Asignaciones procesadas correctamente",
        "data" => [
            "insertados" => $insertados,
            "existentes" => $existentes
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}
exit;