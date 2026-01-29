<?php
session_start();
include_once '../clases/Listados.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$ListaE = new Listados($this);

switch ($rolUsuario) {
    case 'evaluador':
        $sql = Listados::sql_listar_por_evaluador($cedulaSesion);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

$respuesta = $ListaE->listaEvaluadosObreros($sql);
return $respuesta;
