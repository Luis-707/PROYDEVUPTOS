<?php
header('Content-Type: application/json; charset=utf-8');
/* Ajusta la ruta y el nombre de la clase según tu estructura de proyecto */
include_once "../clases/ContieneObjetivo.php";

/*try {
    // Inicializa la clase ContieneObjetivo con los datos necesarios y la conexión
    $contieneObj = new ContieneObjetivo($dataCliente['_post'], $this->conexion);

    // Para "asignar" una relación, se asume que quieres insertar en contiene
    $resultado = $contieneObj->agregarObjetivo();

    // Si la operación devuelve true/false o un identificador depende de tu implementación
    if ($resultado === true || $resultado === 1) {
        echo json_encode(['success' => true, 'message' => 'Objetivo asignado']);
    } else {
        // En caso de que sql devuelva una cadena/mensaje, tratar como éxito si no hay excepción
        echo json_encode(['success' => true, 'message' => 'Objetivo asignado']);
    }
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;*/

//=========================

try {
    $perm = new ContieneObjetivo($dataCliente['_post'], $this->conexion);
    $this->ejecutarConsultaBdds($perm->sql_agregar_objetivo());
    echo json_encode(['success'=>true, 'message'=>'Objetivo asignado']);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;
