<?php

include_once "../clases/EvaluacionAdministrativos.php";

// Suponiendo que tienes los datos de búsqueda en $dataCliente['_post']
$dataCliente['_post']['id_evaluado'] = $dataCliente['_post']['id_evaluado'];
$dataCliente['_post']['periodo_evaluado'] = $dataCliente['_post']['periodo_evaluado'];
$dataCliente['_post']['fecha_inicio'] = $dataCliente['_post']['fecha_inicio'];
$dataCliente['_post']['fecha_cierre'] = $dataCliente['_post']['fecha_cierre'];

// Crear instancia de EvaluacionesAdministrativos
$evaluacion = new EvaluacionesAdministrativos($dataCliente['_post'], $this->conexion);

// Generar la consulta SQL
$sql = $evaluacion->sql_buscarPorFechas();

// Ejecutar la consulta
$respuesta = $this->ejecutarConsultaBdds($sql);

// Verificar si hay resultados
if (empty($respuesta)) {
    return 0;
} else {
    return 1;
}

