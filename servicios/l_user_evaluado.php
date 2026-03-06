<?php
session_start();

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$cedulaSesion    = $_SESSION['usuario']['cedula'] ?? null;

if (!$idUsuarioSesion || !$cedulaSesion) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no autenticado",
        "data" => []
    ]);
    exit;
}

include_once "../clases/Evaluado.php";

$usuario = new Evaluado([], $this);

$sql = $usuario->sql_listar_sub($idUsuarioSesion);
$respuesta = $this->ejecutarConsultaBdds($sql);

echo json_encode([
    "success" => true,
    "message" => "Listado de evaluados",
    "data" => $respuesta
]);
exit;