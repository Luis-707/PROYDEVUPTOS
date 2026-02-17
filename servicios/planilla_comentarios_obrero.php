<?php
session_start();
include_once "../clases/Planilla_comentarios_obreros.php";

// ======================================================
// FUNCIÓN DE NORMALIZACIÓN
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
// CARGA DE POST
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
$planilla = new Planilla_comentarios_obreros($data);

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
$idEval = $planilla->getIdEvalObrero();

// 1. Relaciones
$sqlRel = Planilla_comentarios_obreros::sql_relaciones_por_cedula($cedula, $idEval);
$relacionesRaw = $this->ejecutarConsultaBdds($sqlRel);
$relaciones = normalizarRespuesta($relacionesRaw);

// 2. Datos de la evaluación
$sqlEval = $planilla->sql_buscar();
$evaluacionRaw = $this->ejecutarConsultaBdds($sqlEval);
$evaluacion = normalizarRespuesta($evaluacionRaw);

// 3. Factores
$sqlFact = Planilla_comentarios_obreros::sql_factores();
$factoresRaw = $this->ejecutarConsultaBdds($sqlFact);
$factores = isset($factoresRaw[0]) ? $factoresRaw[0] : [];

// 4. Criterios
$sqlCrit = Planilla_comentarios_obreros::sql_criterios();
$criteriosRaw = $this->ejecutarConsultaBdds($sqlCrit);
$criterios = isset($criteriosRaw[0]) ? $criteriosRaw[0] : [];

// 5. Seleccionados
$sqlSel = Planilla_comentarios_obreros::sql_seleccionados($idEval);
$selRaw = $this->ejecutarConsultaBdds($sqlSel);
$seleccionados = isset($selRaw[0]) ? $selRaw[0] : [];

// ======================================================
// RESPUESTA FINAL
// ======================================================

echo json_encode([
    "success" => true,
    "data" => [
        "relaciones"    => $relaciones,
        "evaluacion"    => $evaluacion,
        "factores"      => $factores,
        "criterios"     => $criterios,
        "seleccionados" => $seleccionados
    ]
]);
exit;