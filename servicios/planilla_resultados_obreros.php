<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/ResultadosObreros.php";

function normalizar($resp) {
    if (isset($resp[0][0])) return $resp[0][0];
    if (isset($resp[0])) return $resp[0];
    return [];
}

try {
    $data = $_POST ?: json_decode(file_get_contents("php://input"), true);

    if (empty($data['id_eval_obreros'])) {
        echo json_encode(["success" => false, "message" => "Falta id_eval_obreros"]);
        exit;
    }

    $idEval = (int)$data['id_eval_obreros'];

    $R = new ResultadosObreros($this);

    // Evaluación
    $eval = normalizar($R->ejecutar(ResultadosObreros::sql_evaluacion_detalle($idEval)));

    // Relaciones
    $rel = normalizar($R->ejecutar(ResultadosObreros::sql_relaciones_resultados($idEval)));

    // Factores
    $fact = $R->ejecutar(ResultadosObreros::sql_factores());
    $factores = $fact[0] ?? [];

    // Criterios
    $crit = $R->ejecutar(ResultadosObreros::sql_criterios());
    $criterios = $crit[0] ?? [];

    // Seleccionados
    $sel = $R->ejecutar(ResultadosObreros::sql_seleccionados($idEval));
    $seleccionados = $sel[0] ?? [];

    echo json_encode([
        "success" => true,
        "data" => [
            "evaluacion"   => $eval,
            "relaciones"   => $rel,
            "factores"     => $factores,
            "criterios"    => $criterios,
            "seleccionados"=> $seleccionados
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
exit;