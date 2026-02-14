<?php
// Comprobar si la variable de sesión 'usuario' está definida
include_once '../servicios/Sesion.php';
?>

<h2>Comentarios para evaluaciones administrativas</h2>

<p class="card-text">
  En esta tabla puede comentar las evaluaciones administrativas donde usted participa como 
  <strong>evaluado</strong> o <strong>supervisor</strong>.
</p>

<table class="table table-bordered align-middle" id="tabla-evaluadosComentarios">
  <thead class="table-dark">
    <tr>
      <th>Cédula</th>
      <th>Apellidos y nombres</th>
      <th>Cargo</th>
      <th>Unidad administrativa</th>
      <th>Año</th>
      <th>Periodo evaluado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
