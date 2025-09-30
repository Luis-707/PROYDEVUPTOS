<?php
include_once "../clases/PlanillaAdministrativos.php";

// Recibir datos
$dataCliente['_post'] = $_POST;

// Asegurarse de que existan los campos necesarios
if (!isset($dataCliente['_post']['id_evaluado']) || !isset($dataCliente['_post']['periodo_evaluado'])) {
    return 0; // faltan datos
}

$evaluacion = new PlanillaAdministrativos($dataCliente['_post']);

// Generar SQL
$sql = $evaluacion->sql_buscar();
error_log("SQL generado: " . $sql);

// Ejecutar
$respuesta = $this->ejecutarConsultaBdds($sql);

// Retornar 0 si no existe, 1 si existe
if (empty($respuesta)) {
    return 0;
} else {
    return 1;
}