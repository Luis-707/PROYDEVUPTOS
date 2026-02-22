<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/RolesSistema.php";

try {
    $rol = new RolesSistema($dataCliente['_post'], $this->conexion);
    $this->ejecutarConsultaBdds($rol->sql_asignar_rol());
    echo json_encode(['success'=>true, 'message'=>'Rol asignado']);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;