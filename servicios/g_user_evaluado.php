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
    // 2) VALIDAR TIPO_EMPLEADO CON TIPO DEL CARGO
    // ============================================================
    $sqlCargo = $evaluado->sql_buscar_cargos(); // JOIN con tipos
    $cargoData = $this->ejecutarConsultaBdds($sqlCargo);
    
    if (empty($cargoData) || empty($cargoData[0][0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Cargo no encontrado.'
        ]);
        exit;
    }
    
    $tipoCargo = $cargoData[0][0]['tipo']; // Tipo del cargo
    
    $tipoEmpleadoPost = $dataCliente['_post']['tipo_empleado'] ?? '';
    $tiposTexto = array_map('trim', explode(',', $tipoEmpleadoPost));
    
    $coincide = false;
    foreach ($tiposTexto as $texto) {
        if ($texto === $tipoCargo) {
            $coincide = true;
            break;
        }
    }
    
    if (!$coincide) {
        echo json_encode([
            'success' => false,
            'message' => "❌ Tipo empleado no válido. Debe ser '{$tipoCargo}' para este cargo."
        ]);
        exit;
    }

    // ============================================================
    // 3) VALIDAR SI YA EXISTE COMO EVALUADO (heredado)
    // ============================================================
    $sql = $evaluado->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {

        // ============================================================
        // 4) INSERTAR NUEVO USUARIO EVALUADO (RETORNA id_usuario + tipo_empleado)
        // ============================================================
        $sqlInsert = $evaluado->sql_guardar();
        $respInsert = $this->ejecutarConsultaBdds($sqlInsert);

        if (empty($respInsert) || empty($respInsert[0][0])) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se pudo crear el usuario evaluado'
            ]);
            exit;
        }

        $nuevoIdUsuario = (int)$respInsert[0][0]['id_usuario'];
        $tipoEmpleado = $respInsert[0][0]['tipo_empleado']; // ← NUEVO: Tipo guardado
        $evaluado->setIdUsuario($nuevoIdUsuario);

        // ============================================================
        // 5) INSERTAR ROL EN posee_rol
        // ============================================================
        $sqlRol = $evaluado->sql_guardar_rol_evaluado();
        $this->ejecutarConsultaBdds($sqlRol);

        // ============================================================
        // 6) ASIGNAR PERMISO SEGÚN TIPO_EMPLEADO (OBRERO vs OTROS)
        // ============================================================
        $permisoId = null;
        $sqlPermiso = null;

        if (stripos($tipoEmpleado, 'Obrero') !== false) {
            // Obrero → Permiso especial
            $sqlPermiso = Evaluado::sql_buscar_comentarios_obreros();
            $permisoUsado = 'Comentarios obreros';
        } else {
            // Otros → Permiso estándar
            $sqlPermiso = Evaluado::sql_buscar_permiso_comentarios();
            $permisoUsado = 'Comentarios';
        }

        $respPermiso = $this->ejecutarConsultaBdds($sqlPermiso);

        if (empty($respPermiso) || empty($respPermiso[0][0]['permisos_id'])) {
            echo json_encode([
                'success' => false,
                'message' => "❌ No se encontró el permiso '{$permisoUsado}'"
            ]);
            exit;
        }

        $permisoId = (int)$respPermiso[0][0]['permisos_id'];

        // ============================================================
        // 7) INSERTAR PERMISO EN posee_permisos
        // ============================================================
        $sqlPosee = $evaluado->sql_guardar_permiso($permisoId);
        $this->ejecutarConsultaBdds($sqlPosee);

        // ============================================================
        // 8) RESPUESTA FINAL
        // ============================================================
        echo json_encode([
            'success'       => true,
            'message'       => '✅ Usuario evaluado creado con éxito y permiso asignado',
            'id_usuario'    => $nuevoIdUsuario,
            'permisos_id'   => $permisoId,
            'tipo_empleado' => $tipoEmpleado,
            'permiso_usado' => $permisoUsado,
            'tipo_cargo'    => $tipoCargo
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