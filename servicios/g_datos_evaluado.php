<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

session_start();
include_once "../clases/DatosEvaluados.php";

try {
    // 1) Validar sesión
    $idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
    if (!$idUsuarioSesion) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Usuario no autenticado'
        ]);
        exit;
    }

    // 2) Instanciar la clase con los datos del POST y la conexión
    $evaluado = new DatosEvaluados($dataCliente['_post'], $this->conexion);

    // 3) Buscar id_evaluador asociado al usuario en sesión
    $sqlEvaluador = DatosEvaluados::sql_buscar_evaluador_por_usuario($idUsuarioSesion);
    $resEvaluador = $this->ejecutarConsultaBdds($sqlEvaluador);

    if (empty($resEvaluador) || empty($resEvaluador[0][0]['id_evaluador'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ El usuario en sesión no está registrado como evaluador'
        ]);
        exit;
    }

    $idEvaluador = (int)$resEvaluador[0][0]['id_evaluador'];
    $evaluado->setIdEvaluador($idEvaluador);

    // 4) Verificar si ya existe un registro para ese usuario evaluado
    $sql = $evaluado->sql_buscar_evaluados();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {
        // 5) Insertar nuevo evaluado con cargo y evaluador
        $sql = DatosEvaluados::sql_guardar_evaluado(
            $evaluado->getIdUsuario(),       // id_usuario del evaluado (POST)
            $evaluado->getIdCargoEvaluado(), // id_cargo_evaluado (POST)
            $evaluado->getIdEvaluador()      // id_evaluador (de la sesión)
        );
        $respInsert = $this->ejecutarConsultaBdds($sql);

        if (empty($respInsert) || empty($respInsert[0][0]['id_evaluado'])) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se pudo crear el evaluado'
            ]);
            exit;
        }

        $nuevoIdEvaluado = (int)$respInsert[0][0]['id_evaluado'];

        // 6) Respuesta final
        echo json_encode([
            'success'     => true,
            'message'     => '✅ Evaluado creado con éxito',
            'id_evaluado' => $nuevoIdEvaluado
        ]);

    } else {
        // Ya existe el evaluado
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe un evaluado para este usuario'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;