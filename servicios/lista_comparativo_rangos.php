<?php
session_start();
include_once '../clases/ResultadosAdmin.php';

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;

if (!$idUsuarioSesion) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no autenticado"
    ]);
    exit;
}

// Parámetros recibidos desde microApi()
$anioActual = isset($_POST['anio_actual']) ? (int) $_POST['anio_actual'] : null;
$periodo    = isset($_POST['periodo']) ? (int) $_POST['periodo'] : null;

if (!$anioActual || !in_array($periodo, [1, 2], true)) {
    echo json_encode([
        "success" => false,
        "message" => "Parámetros inválidos. Se requiere anio_actual y periodo (1 o 2)."
    ]);
    exit;
}

$Resultados = new ResultadosAdmin($this);

// SQL final corregida
$sql = ResultadosAdmin::sql_comparativo_rangos_semestrales($anioActual, $periodo);

// Ejecutar consulta
$respuesta = $Resultados->listarResultados($sql);

echo json_encode([
    "success" => true,
    "data"    => $respuesta
]);
exit;