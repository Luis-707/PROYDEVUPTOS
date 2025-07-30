<?php //print_r($_POST);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

// Lee el cuerpo de la solicitud
$rawData = file_get_contents('php://input');

// Decodifica el JSON en un array asociativo
$data = json_decode($rawData, true);
$_POST = $data;
if (isset($_POST['file']) && isset($_POST['idElemento'])) {
    $file = $_POST['file'];
    $idElemento = $_POST['idElemento'];

    $DIR = '../views/';
    // Define un mapeo de nombres de archivos permitidos
    $allowedFiles = [
        'vistaDemo' => $DIR. 'form-demo.php',
        'persona' => $DIR. 'vpersona.php',
        'usuario' => $DIR. 'vusuario.php',
        'empleado' => $DIR. 'vempleado.php'

    ];/*
    echo $file;
    die(print_r($allowedFiles));*/
    // Verifica si el archivo solicitado está permitido
    if (array_key_exists($file, $allowedFiles)) {
        $filePath = $allowedFiles[$file];
         //echo "Archivo = ".$filePath;
        // Incluye el archivo PHP
        if (file_exists($filePath)) {
            include_once $filePath;
        } else {
            echo "Error: Archivo no encontrado.";
        }
    } else {
        echo "Archivo no permitido.";
    }
} else {
    echo "Parámetros incompletos.";
}
?>