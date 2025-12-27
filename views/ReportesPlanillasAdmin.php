<?php
include_once '../servicios/Sesion.php';
?>

<h2>Reportes de Evaluaciones Administrativas</h2>

<p class="card-text">
  En esta tabla puede acceder a los reportes en PDF de las evaluaciones realizadas,
  siempre que los campos de comentarios y conformidad estén completos.
</p>

<table class="table table-bordered align-middle" id="tabla-reportes">
  <thead class="table-dark">
    <tr>
      <th>Cédula</th>
      <th>Apellidos y nombres</th>
      <th>Cargo</th>
      <th>Año</th>
      <th>Periodo</th>
      <th>Conformidad</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
