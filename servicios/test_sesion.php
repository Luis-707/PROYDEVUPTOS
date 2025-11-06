<?php
// Forzar salida JSON limpia
header('Content-Type: application/json; charset=utf-8');
ob_clean();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once "../clases/Evaluados.php";

// 1. Ver qué hay en la sesión
$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;

// 2. Preparar consultas de prueba
$sqlUsuario = $cedulaSesion ? Evaluado::sql_buscar_usuario_por_cedula($cedulaSesion) : null;
$sqlEvaluador = $idUsuarioSesion ? Evaluado::sql_buscar_evaluador_por_usuario((int)$idUsuarioSesion) : null;

// 3. Devolver todo en JSON
echo json_encode([
    'success' => true,
    'message' => 'Prueba de sesión y SQL',
    'session' => $_SESSION,
    'cedulaSesion' => $cedulaSesion,
    'idUsuarioSesion' => $idUsuarioSesion,
    'sqlUsuario' => $sqlUsuario,
    'sqlEvaluador' => $sqlEvaluador
]);
exit;