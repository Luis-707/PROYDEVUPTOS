<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Planilla_comentarios.php";

try {
    // Inicializar dataCliente
    if (!isset($dataCliente)) {
        $dataCliente = ['_post' => $_POST];
        if (empty($dataCliente['_post'])) {
            $json = file_get_contents("php://input");
            $dataCliente['_post'] = json_decode($json, true) ?? [];
        }
    }

    // Validar cédula
    if (empty($dataCliente['_post']['cedula_usuario'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se recibió la cédula del evaluado'
        ]);
        exit;
    }
    $cedula = $dataCliente['_post']['cedula_usuario'];

    // Instanciar clase con conexión
    $planilla = new Planilla_comentarios(['cedula_usuario' => $cedula], $this->conexion);

    // 1. Ejecutar consulta de evaluación
    $sqlEval = $planilla->sql_buscar();
    $evaluaciones = $this->ejecutarConsultaBdds($sqlEval);

    // Normalizar: tomar la primera fila directamente
    if (isset($evaluaciones[0][0])) {
        $evalData = $evaluaciones[0][0];
    } else {
        $evalData = $evaluaciones[0] ?? [];
    }

    if (empty($evalData)) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró evaluación registrada para este usuario'
        ]);
        exit;
    }

    $planilla->id_eval_admin = (int)$evalData['id_eval_admin'];

    // 2. Objetivos (con tabla contiene)
    $sqlObj = $planilla->sql_objetivos_por_cedula($cedula);
    $objetivos = $this->ejecutarConsultaBdds($sqlObj);
    if (isset($objetivos[0][0])) $objetivos = $objetivos[0];

    // 3. Competencias
    $sqlComp = $planilla->sql_competencias($planilla->id_eval_admin);
    $competencias = $this->ejecutarConsultaBdds($sqlComp);
    if (isset($competencias[0][0])) $competencias = $competencias[0];

    // 4. Relaciones (cargos + cédulas)
    $sqlRel = $planilla->sql_relaciones_por_cedula($cedula);
    $relaciones = $this->ejecutarConsultaBdds($sqlRel);

    // Normalizar igual que evaluación
    if (isset($relaciones[0][0])) {
        $relacionData = $relaciones[0][0];
    } else {
        $relacionData = $relaciones[0] ?? null;
    }

    // 5. Fusionar cargos en evaluación
    if ($relacionData) {
        $evalData['cargo_evaluado']   = $relacionData['cargo_evaluado'] ?? null;
        $evalData['cargo_evaluador']  = $relacionData['cargo_evaluador'] ?? null;
        $evalData['cargo_supervisor'] = $relacionData['cargo_supervisor'] ?? null;
    }

    // 6. Respuesta
    echo json_encode([
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'data' => [
            'evaluacion'   => $evalData,
            'objetivos'    => $objetivos,
            'competencias' => $competencias,
            'relaciones'   => $relacionData
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;