<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/RolesSistema.php";

try {
    $rol = new RolesSistema($dataCliente['_post'], $this->conexion);
    $respuesta = $this->ejecutarConsultaBdds($rol->sql_listar_rol());
    echo json_encode($respuesta ?: []);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;