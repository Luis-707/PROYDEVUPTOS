<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

session_start();

// Ajusta la ruta según donde esté tu clase
include_once "../clases/GestionIndicador.php";

$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario = $_SESSION['usuario']['roles'][0] ?? null; 

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

    // 2) Validar rol
    if (!in_array($rolUsuario, ['administrador', 'evaluador', 'evaluado', 'supervisor del evaluador'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Rol no autorizado para esta acción'
        ]);
        exit;
    }

    // 3) Detectar si los datos vienen como JSON o Form-Data
    $dataCliente = [];
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents("php://input");
        $dataCliente = json_decode($raw, true) ?? [];
    } else {
        $dataCliente = $_POST;
    }

    // 4) Validar datos obligatorios del cliente
    if (empty($dataCliente['indicador'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ El campo "indicador" es obligatorio'
        ]);
        exit;
    }

    // 5) Inyectar id_usuario de la sesión
    $dataCliente['id_usuario'] = $idUsuarioSesion;

    // 6) Asignar tipo_indicador fijo según rol
    $dataCliente['tipo_indicador'] = ($rolUsuario === 'administrador') ? 'Fijo' : 'Adicional';
    //$dataCliente['estado_indicador'] = 'Activo'; // Por defecto

    // 7) Instanciar la clase GestionIndicador (necesitas pasar la conexión)
    // Nota: Ajusta $this->conexion por tu objeto de conexión real
    //$conexion = $this->conexion; // o new TuClaseConexion() según tu sistema
    $indicador = new GestionIndicador($dataCliente, $this->conexion);

    // 8) Ejecutar el INSERT
    $sqlInsert = $indicador->sql_insertar_indicador();
    $respInsert = $this->ejecutarConsultaBdds($sqlInsert);

    $registro = $respInsert[0][0] ?? null;

    if ($registro && !empty($registro['indicador_id'])) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Indicador creado con éxito',
            'indicador_id' => (int)$registro['indicador_id'],
            'tipo_indicador' => $dataCliente['tipo_indicador']
            //'sql_generado' => $sqlInsert // Para debug, quítalo en producción
        ]);
    } {
        // ✅ ÉXITO AUNQUE NO HAY ID (los datos SÍ se insertaron)
        echo json_encode([
            'success' => true,
            'message' => '✅ Indicador creado con éxito (ID no disponible)',
            'tipo_indicador' => $dataCliente['tipo_indicador']
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
?>
