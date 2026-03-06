<?php
include_once "../clases/EvaluacionesObreros.php";

$data = $dataCliente['_post'];

$eval = new EvaluacionesObreros($dataCliente['_post'], $this->conexion);

// 1) Verificar que el registro exista
$resp = $this->ejecutarConsultaBdds($eval->sql_buscar_evalObrero_id());

if (empty($resp)) {
    echo json_encode([
        'success' => false,
        'message' => $data['id_eval_obreros'] . ' No Existe'
    ]);
    exit;
}

// 2) Validar duplicado de período (NUEVO)
$sqlDup = $eval->sql_existe_duplicado_periodo_obrero_edicion();
$respDup = $this->ejecutarConsultaBdds($sqlDup);

if (!empty($respDup) && !empty($respDup[0][0]['id_eval_obreros'])) {
    echo json_encode([
        'success' => false,
        'message' => '❌ No puede cambiar el período: ya existe otra evaluación de este evaluado en ese período'
    ]);
    exit;
}

// 3) Actualizar período
$this->ejecutarConsultaBdds($eval->sql_actualizar_periodo_obrero());

// 4) Refrescar tabla
echo json_encode([
    'success' => true,
    'message' => 'Periodo actualizado correctamente',
    'data' => $this->servicio($data, 'l_evalOb')
]);
exit;