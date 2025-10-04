<?php
include_once "../clases/PlanillaAdministrativos.php";
include_once "../controlador.php"; // 👈 para usar la clase Controlador

header('Content-Type: application/json; charset=utf-8');

try {
    // Decodificar arrays recibidos desde JS
    $objetivos = json_decode($_POST['objetivos'] ?? "[]", true);
    $competencias = json_decode($_POST['competencias'] ?? "[]", true);

    // Crear el objeto Controlador (tu "conexión")
    $conexion = new Controlador(""); 

    // Instanciar la clase con esa conexión
    $planilla = new PlanillaAdministrativos([], $conexion);

    $resultados = [
        "objetivos" => [],
        "competencias" => []
    ];

    // Guardar objetivos
    if (!empty($objetivos)) {
        foreach ($objetivos as $obj) {
            $sqlObj = $planilla->sql_guardar_objetivo(
                $obj['id_odi'],
                $obj['rango'],
                $obj['pesoXRango']
            );

            error_log("SQL OBJ: " . $sqlObj);
            error_log("Datos OBJ: " . json_encode($obj));

            $res = $conexion->ejecutarConsultaBdds($sqlObj);
            $resultados["objetivos"][] = $res;
        }
    }

    // Guardar competencias
    if (!empty($competencias)) {
        foreach ($competencias as $comp) {
            $sqlComp = $planilla->sql_guardar_competencia(
                $comp['id_competencia'],
                $comp['rango'],
                $comp['pesoXRango']
            );

            error_log("SQL COMP: " . $sqlComp);
        error_log("Datos COMP: " . json_encode($comp));

            
            $res = $conexion->ejecutarConsultaBdds($sqlComp);
            $resultados["competencias"][] = $res;
        }
    }

    echo json_encode(["success" => true, "data" => $resultados]);
    exit;
} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}