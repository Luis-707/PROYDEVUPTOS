<?php
session_start();
include_once "../clases/Planilla_comentarios.php";

// ======================================================
// FUNCIÓN DE NORMALIZACIÓN (del servicio original)
// ======================================================
function normalizarRespuesta($resp) {
    if (isset($resp[0][0])) {
        return $resp[0][0];
    } elseif (isset($resp[0])) {
        return $resp[0];
    }
    return [];
}

// ======================================================
// CARGA DE POST (del servicio original)
// ======================================================
if (!isset($dataCliente)) {
    $dataCliente = ['_post' => $_POST];
    if (empty($dataCliente['_post'])) {
        $json = file_get_contents("php://input");
        $dataCliente['_post'] = json_decode($json, true) ?? [];
    }
}

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolesSesion  = $_SESSION['usuario']['roles'] ?? [];

if (!$cedulaSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$data = $dataCliente['_post'] ?? $_POST ?? [];
$planilla = new Planilla_comentarios($data);

// ======================================================
// VALIDACIÓN DE PERMISOS
// ======================================================

// Caso 1: Usuario es EVALUADO
if (in_array("evaluado", $rolesSesion)) {

    $sql = $planilla->sql_buscar_por_id_y_evaluado($cedulaSesion);
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autorizado para ver esta evaluación'
        ]);
        exit;
    }
}

// Caso 2: Usuario es SUPERVISOR DEL EVALUADOR
elseif (in_array("supervisor del evaluador", $rolesSesion)) {

    $sql = $planilla->sql_buscar_por_id_y_supervisor($cedulaSesion);
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autorizado para ver esta evaluación'
        ]);
        exit;
    }
}

// Caso 3: Ningún rol válido
else {
    echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
    exit;
}

// ======================================================
// SI PASÓ LA VALIDACIÓN → CARGAR LA PLANILLA COMPLETA
// ======================================================

$cedula = $planilla->getCedulaUsuario();
$idEval = $planilla->getIdEvalAdmin();

// 1. Relaciones
$sqlRel = Planilla_comentarios::sql_relaciones_por_cedula($cedula);
$relacionesRaw = $this->ejecutarConsultaBdds($sqlRel);
$relaciones = normalizarRespuesta($relacionesRaw);

// 2. Datos de la evaluación
$sqlEval = $planilla->sql_buscar();
$evaluacionRaw = $this->ejecutarConsultaBdds($sqlEval);
$evaluacion = normalizarRespuesta($evaluacionRaw);

// 3. Objetivos
$sqlObj = Planilla_comentarios::sql_objetivos_por_cedula($cedula, $idEval);
$objetivosRaw = $this->ejecutarConsultaBdds($sqlObj);
$objetivos = isset($objetivosRaw[0]) && is_array($objetivosRaw[0])
    ? $objetivosRaw[0]
    : $objetivosRaw;

// 4. Competencias
$sqlComp = Planilla_comentarios::sql_competencias($idEval);
$competenciasRaw = $this->ejecutarConsultaBdds($sqlComp);
$competencias = isset($competenciasRaw[0]) && is_array($competenciasRaw[0])
    ? $competenciasRaw[0]
    : $competenciasRaw;

// ======================================================
// RESPUESTA FINAL
// ======================================================

echo json_encode([
    "success" => true,
    "data" => [
        "relaciones"  => $relaciones,
        "evaluacion"  => $evaluacion,
        "objetivos"   => $objetivos,
        "competencias"=> $competencias
    ]
]);
exit;