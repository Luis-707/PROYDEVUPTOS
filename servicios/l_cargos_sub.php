<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

// Iniciar sesión para verificar autenticación
session_start();

$idUsuarioSesionCargo = $_SESSION['usuario']['id_cargo'] ?? null;
$cedulaSesion    = $_SESSION['usuario']['cedula'] ?? null;

// Verificar que el usuario esté autenticado (sin rol)
if (!$idUsuarioSesionCargo || !$cedulaSesion) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

include_once "../clases/Evaluado.php";

$usuario = new Evaluado([], $this);

// Pasar el ID del usuario autenticado al método sql_cargos_sub
$sql = $usuario->sql_cargos_sub($idUsuarioSesionCargo);
             
$respuesta = $this->ejecutarConsultaBdds($sql);
return $respuesta;