<?php
session_start();
include_once '../clases/PlanillaAdministrativos.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

$PlanillaAdmin = new PlanillaAdministrativos([],$this);

if ($rolUsuario === 'evaluador') {
    $sql = PlanillaAdministrativos::sql_listar_relaciones($cedulaSesion);
} elseif ($rolUsuario === 'admin') {
    $sql = PlanillaAdministrativos::sql_listar_relaciones();
} else {
    echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
    exit;
}

$respuesta = $this->ejecutarConsultaBdds($sql);
return $respuesta;