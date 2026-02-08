<?php
session_start();
include_once '../clases/Listados.php';

$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
$rolUsuario = $_SESSION['usuario']['rol'] ?? null;

if (!$id_usuario || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Lista = new Listados($this);

switch ($rolUsuario) {
    case 'evaluador':
        $sql = Listados::sql_listar_por_registro_Obreros($_SESSION['usuario']['cedula']);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

$respuesta = $Lista->listaObreros($sql);
return $respuesta;
