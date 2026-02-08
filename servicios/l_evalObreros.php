<?php
include_once "../clases/EvaluacionesObreros.php";

$eval = new EvaluacionesObreros([], $this->conexion);

return $eval->listarEvalObreros();

