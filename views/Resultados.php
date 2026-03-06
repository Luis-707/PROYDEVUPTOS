<?php
// Comprobar si la variable de sesión 'usuario' está definida
include_once '../servicios/Sesion.php';
?>

<div class="card mb-4">
  <div class="card-header">
    Distribución por rango de actuación
  </div>

  <div class="card-body">

    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Filtrar por período:</label>
        <select id="filtroPeriodo" class="form-select">
          <option value="todos">Todos los períodos</option>
        </select>
      </div>
    </div>

    <canvas id="graficaRangos" height="120"></canvas>

  </div>
</div>


<h2>Resultados de las evaluaciones de desempeño del personal administrativo</h2>

<p class="card-text">
  En esta tabla puede visualizar las evaluaciones de un empleado cuya evaluación usted ha supervisado o Calificado.
</p>

<table class="table table-bordered align-middle" id="tabla-evaluadosResultados">
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