<?php
session_start();
include_once "../clases/Listados.php";

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$cedulaSesion    = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario      = $_SESSION['usuario']['rol'] ?? null;

if (!$idUsuarioSesion || !$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Evaluado = new Listados($this);

switch ($rolUsuario) {
    case 'evaluador':
        // 1) Buscar id_evaluador del usuario en sesión
        $sqlEvaluador = Listados::sql_buscar_evaluador_por_usuario($idUsuarioSesion);
        $resEvaluador = $this->ejecutarConsultaBdds($sqlEvaluador);

        if (empty($resEvaluador) || empty($resEvaluador[0][0]['id_evaluador'])) {
            echo json_encode(["success" => false, "message" => "El usuario en sesión no está registrado como evaluador"]);
            exit;
        }

        $idEvaluador = (int)$resEvaluador[0][0]['id_evaluador'];

        // 2) Listar todos los evaluados (registrados + no registrados)
        $sql = Listados::sql_listar_todos_evaluados_union($cedulaSesion, $idEvaluador);
        $respuesta = $Evaluado->ejecutarConsulta($sql);
        break;

    /*case 'administrador':
        // Todos los evaluados
        $respuesta = $Evaluado->ejecutarConsulta(Listados::sql_listar_evaluados());
        break;*/

    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

return $respuesta;