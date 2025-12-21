<?php
session_start();
include_once '../clases/Listados.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$ListaR = new Listados($this);

// Seleccionar SQL según rol
switch ($rolUsuario) {
    case 'evaluador':
        $sql = Listados::sql_reportes_por_evaluador($cedulaSesion);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

$respuesta = $ListaR->listarReportes($sql);
return $respuesta;
