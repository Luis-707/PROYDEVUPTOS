<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    // $dataCliente debe estar disponible en tu contexto (igual que en el otro servicio)
    // Si no, puedes inicializarlo así:
    if (!isset($dataCliente)) {
        $dataCliente = ['_post' => $_POST];
        if (empty($dataCliente['_post'])) {
            $json = file_get_contents("php://input");
            $dataCliente['_post'] = json_decode($json, true) ?? [];
        }
    }

    // Validar que venga la cédula
    if (empty($dataCliente['_post']['cedula_usuario'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se recibió la cédula del evaluado'
        ]);
        exit;
    }

    // Instanciar la clase con los datos y la conexión
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // Ejecutar la consulta de relaciones
    $sql = $planilla->sql_listar_relaciones();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    // Normalizar respuesta
    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontraron datos para la cédula indicada'
        ]);
        exit;
    }

    // Filtrar por la cédula solicitada
    $cedula = $dataCliente['_post']['cedula_usuario'];
    $registro = null;
    foreach ($respuesta[0] as $fila) {
        if ($fila['cedula_usuario'] === $cedula) {
            $registro = $fila;
            break;
        }
    }

    if (!$registro) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró el evaluado con esa cédula'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'data' => $registro
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;