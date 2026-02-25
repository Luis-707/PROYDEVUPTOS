<?php

error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Evaluado.php";

try {
    $evaluado = new Evaluado($dataCliente['_post'], $this->conexion);

    // ============================================================
    // 1) VALIDAR SI YA EXISTE UN USUARIO CON ESA CÉDULA
    // ============================================================
    $sqlValidar = $evaluado->sql_validar_cedula_evaluado();
    $existeCedula = $this->ejecutarConsultaBdds($sqlValidar);

    if (count($existeCedula) > 0) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Ya existe un usuario registrado con esta cédula.'
        ]);
        exit;
    }

    // ============================================================
    // 2) VALIDAR SI YA EXISTE COMO EVALUADO (sql_buscar heredado)
    // ============================================================
    $sql = $evaluado->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {

        // ============================================================
        // 3) INSERTAR NUEVO USUARIO EVALUADO
        // ============================================================
        $sqlInsert = $evaluado->sql_guardar();
        $respInsert = $this->ejecutarConsultaBdds($sqlInsert);

        if (empty($respInsert) || empty($respInsert[0][0]['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se pudo crear el usuario evaluado'
            ]);
            exit;
        }

        $nuevoIdUsuario = (int)$respInsert[0][0]['id_usuario'];
        $evaluado->setIdUsuario($nuevoIdUsuario);

        // ============================================================
        // 4) BUSCAR PERMISO "Comentarios"
        // ============================================================
        $sqlPermiso = Evaluado::sql_buscar_permiso_comentarios();
        $respPermiso = $this->ejecutarConsultaBdds($sqlPermiso);

        if (empty($respPermiso) || empty($respPermiso[0][0]['permisos_id'])) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se encontró el permiso Comentarios'
            ]);
            exit;
        }

        $permisoId = (int)$respPermiso[0][0]['permisos_id'];

        // ============================================================
        // 5) INSERTAR PERMISO EN posee_permisos
        // ============================================================
        $sqlPosee = $evaluado->sql_guardar_permiso($permisoId);
        $this->ejecutarConsultaBdds($sqlPosee);

        // ============================================================
        // 6) RESPUESTA FINAL
        // ============================================================
        echo json_encode([
            'success'    => true,
            'message'    => '✅ Usuario evaluado creado con éxito y permiso asignado',
            'id_usuario' => $nuevoIdUsuario,
            'permisos_id'=> $permisoId
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => '❌ Ya existe un usuario evaluado con esta cédula.'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;