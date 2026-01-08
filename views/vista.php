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
        /*'vistaDemo' => $DIR. 'form-demo.php',
        'persona' => $DIR. 'vpersona.php',
        'usuario' => $DIR. 'vusuario.php',*/
        'usuarios' => $DIR. 'form.php',
        'evaluadores' => $DIR. 'Gestion_Evaluador.php',
        'supervisores' => $DIR. 'Gestion_Supervisor.php',
        'evaluacion' => $DIR. 'evaluacion.php',
        'comentarios' => $DIR. 'Comentarios.php',
        'resultados' => $DIR. 'Resultados.php',
        'planilla' => $DIR. 'planilla.php',
        'planilla_comentario' => $DIR. 'Planilla_Comentarios.php',
        'planilla_resultados' => $DIR. 'Planilla_Resultados.php',
        'planilla_editar' => $DIR. 'planilla_editar.php',
        'gestion_evaluados' => $DIR. 'GestionEvaluados.php',
        'evaluacion_administrativos' => $DIR. 'EvalAministrativos.php',
        'cargos_evaluados' => $DIR. 'Datos_Evaluados.php',
        'gestion_objetivos' => $DIR. 'GestionObjetivos.php',
        'gestion_competencias' => $DIR. 'GestionCompetencias.php',
        'perfilUsuario' => $DIR. 'perfilUsuario.php',
        'reportes_despempeno' => $DIR. 'reportes_desemp.php',
        'reportes_administrativos' => $DIR. 'ReportesPlanillasAdmin.php',
        'planilla_excepcional' => $DIR. 'Planilla_Excepcional.php',
        //'Registro_Evaluados' => $DIR. 'Registro_Evaluados.php'
        /*'empleado' => $DIR. 'vempleado.php'*/

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