<?php
include_once "../clases/Usuario3.php";

  //$dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];
  
//print_r($dataCliente['_post']);

if (isset($dataCliente['_post']['otros_datos'])) {
  $dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];
}

  $perfil = new Usuario($dataCliente['_post']);

  $sql = $perfil->sql_buscar();
  $respuesta = $this->ejecutarConsultaBdds($sql);

  if (count($respuesta) == 0) {     
    $respuesta = $dataCliente['_post']['cedula_usuario'].' No Existe';
  }else{
    $sql = $perfil->sql_perfil_usuario();
    $respuesta = $this->ejecutarConsultaBdds($sql);
  }
  
  return $respuesta;

  /*$data=$dataCliente['_post'];
  // var_dump($data['nombres']);
  
  $perfil = new Usuario( $dataCliente['_post']);
  $sql = $perfil->sql_buscar();
  $respuesta = $this->ejecutarConsultaBdds($sql);

  if (count($respuesta) == 0) {     
    $respuesta = $dataCliente['_post']['cedula_usuario'].' No Existe';
   
  }else{
    $sql=$perfil->sql_perfil_usuario();
    $respuesta = $this->ejecutarConsultaBdds($sql);
  }
  
  
  //$respuesta = $this->ejecutarConsultaBdds($sql);
  //$respuesta = $this->servicio($data,'l_user'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
  return $respuesta;
  

/*error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Usuario.php";

// Obtención de datos POST o JSON
$dataCliente = ['_post' => $_POST];
if (empty($dataCliente['_post'])) {
    $json = file_get_contents("php://input");
    $dataCliente['_post'] = json_decode($json, true) ?? [];
}

// Validar que venga la cédula
if (empty($dataCliente['_post']['cedula_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se recibió la cédula del usuario'
    ]);
    exit;
}

// Asumiendo que existe un objeto $conexion para la base de datos
//$conexion = new Conexion(); // Cambiar según tu clase/objeto de conexión real

// Instanciar objeto Usuario con la cédula y la conexión
$usuario = new Usuario(['cedula_usuario' => $dataCliente['_post']['cedula_usuario']], $conexion);

// Construir la consulta para perfil usuario
$sql = $usuario->sql_perfil_usuario();

// Ejecutar consulta y capturar resultado
$resultado = $conexion->ejecutarConsultaBdds($sql);

if (!$resultado || empty($resultado[0])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se encontraron datos para la cédula indicada'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Datos de perfil cargados correctamente',
    'data' => $resultado[0]
]);

exit;*/
?>
