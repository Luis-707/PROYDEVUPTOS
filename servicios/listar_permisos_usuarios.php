<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/PermisosUsuario.php";

try {
    $perm = new PermisosUsuario($dataCliente['_post'], $this->conexion);
    $respuesta = $this->ejecutarConsultaBdds($perm->sql_listar());
    echo json_encode($respuesta ?: []);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;