<?php

/**
 *  Ofrece una lista de los servicio de la API prestados
 * 
 *  @return array Lista de servicios con su descripcion y estatus.
 *  @author UPTOS_CR Luis M. Duran  
 *  @status 1 // valor '1'=> Activo, '0'=>Inactivo' 
 * 
 */
/*
 * recursos: 
 *       ejecutar sqls: $respuesta = $this->ejecutarConsultaBdds($cadenaSql);
 * array  $dataCliente: contiene los datos enviados desde las vista o interfaz
 * llamar servicio dede desvicio:
 *      %this->servicio([array]$dataEviada,'nombreservicio')
 */

$dir = '../servicios';
$files = glob($dir . '/*.php');
$result = [];

for ($i = 0; $i < count($files); $i++) {
    $file = $files[$i];
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $description = '';
    $in_comment = false;
    $status = null;

    foreach ($lines as $line) {
        if (strpos($line, '/**') === 0) {
            $in_comment = true;
            $description .= $line . "\n";
        } elseif (strpos($line, '*/') !== false) {
            $description .= $line;
            break;
        } elseif ($in_comment) {
            $description .= $line . "\n";
            if (strpos($line, '@status') !== false) {
                $status = trim(substr($line, strpos($line, '@status') + 7));
            }
        }
    }

    $filename = basename($file, '.php');
    $result[] = array(
        'servicio' => $filename,
        'descripcion' => $description,
        'status' => $status
    );
}

return $result;
