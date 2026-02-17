<?php
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Listados.php";

session_start();

try {

    // ============================
    // Validar sesión
    // ============================
    $idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
    $rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

    if (!$idUsuarioSesion || empty($rolesSesion)) {
        echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
        exit;
    }

    // ============================
    // Solo permitir rol evaluador
    // ============================
    if (!in_array("evaluador", $rolesSesion)) {
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
    }

    // ============================
    // Obtener SQL desde la clase Listados
    // ============================
    $sql = Listados::sql_listar_eval_obreros($idUsuarioSesion);

    // ============================
    // Ejecutar consulta
    // ============================
    $Listados = new Listados($this);
    $resp = $this->ejecutarConsultaBdds($sql);

    if (empty($resp) || empty($resp[0])) {
        echo json_encode(["success" => true, "data" => []]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "data" => $resp[0]
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

exit;