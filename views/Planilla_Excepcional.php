<?php
include_once '../servicios/Sesion.php';
?>

<style>
  .table-bordered th, .table-bordered td {
    border: 1px solid #dee2e6;
  }
  .badge-status {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
  }
  .badge-ok { background: #e6ffed; color: #046b1f; border: 1px solid #b7f5c5; }
  .badge-warn { background: #fff4e5; color: #7a3d00; border: 1px solid #ffd6a8; }
  .indicador-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
    background: #fafafa;
  }
  .indicador-title {
    font-weight: 600;
    margin-bottom: 6px;
  }
  .help-text {
    font-size: 12px;
    color: #6c757d;
  }
</style>

<div class="container mt-4">
  <h3>Planilla de Desempeño Excepcional</h3>
  <h5>Nivel Administrativo</h5>

  <!-- Estado de habilitación -->
  <div id="estado-excepcional" class="mt-3">
    <span class="badge-status badge-warn" id="badge-excepcional">
      Opción inactiva: requiere rango "Desempeño excepcional"
    </span>
  </div>

  <form id="form_excepcional" onsubmit="event.preventDefault(); guardarDesempenoExcepcional();">
    <!-- Sección A: Datos de identificación -->
    <div class="row mt-4">
      <div class="col-md-12">
        <h6 class="text-muted d-flex justify-content-between">
          <span>SECCIÓN "A":</span>
          <span>DATOS DE IDENTIFICACIÓN</span>
        </h6>
      </div>

      <div class="col-md-6 mt-2">
        <ul class="list-group">
          <li class="list-group-item"><strong>Nombre:</strong> <span id="ex_eval_fullname"></span></li>
          <li class="list-group-item"><strong>Cédula:</strong> <span id="ex_eval_cedula"></span></li>
          <li class="list-group-item"><strong>Cargo:</strong> <span id="ex_eval_cargo"></span></li>
          <li class="list-group-item"><strong>Ubicación administrativa:</strong> <span id="ex_eval_ubicacion"></span></li>
        </ul>
      </div>

      <div class="col-md-6 mt-2">
        <ul class="list-group">
          <li class="list-group-item"><strong>Periodo de evaluación:</strong> <span id="ex_periodo_texto"></span></li>
          <li class="list-group-item"><strong>Año(s) del periodo:</strong> <span id="ex_periodo_anio"></span></li>
          <li class="list-group-item"><strong>Puntaje final:</strong> <span id="ex_puntaje_final">0</span></li>
          <li class="list-group-item"><strong>Rango de actuación:</strong> <span id="ex_rango_actuacion">N/D</span></li>
          <li class="list-group-item"><strong>Fecha de emisión:</strong> <span id="ex_fecha_emision"></span></li>
        </ul>
      </div>

      <!-- Hidden inputs -->
      <input type="hidden" id="ex_id_eval_admin" name="id_eval_admin">
      <input type="hidden" id="ex_periodo" name="periodo">
      <input type="hidden" id="ex_fecha" name="fecha">
    </div>

    <!-- Sección B: Indicadores -->
    <div class="row mt-4">
      <div class="col-md-12">
        <h6 class="text-muted d-flex justify-content-between">
          <span>SECCIÓN "B":</span>
          <span>Exposición de Motivos / Asignación de Rango Excepcional</span>
        </h6>
        <p class="help-text">
          Debe completar los motivos para cada indicador. Estos indicadores son fijos y representan los criterios del desempeño excepcional.
        </p>
      </div>

      <div class="col-md-12" id="contenedor-indicadores"></div>
    </div>

    <!-- Botón Guardar -->
    <div class="row mt-4">
      <div class="col-md-12 text-center">
        <button type="submit" id="btn_guardar_excepcional" class="btn btn-primary" disabled>
          Guardar Planilla de Desempeño Excepcional
        </button>
        <p class="help-text mt-2">El botón se habilita automáticamente si el rango es “Desempeño excepcional”.</p>
      </div>
    </div>
  </form>
</div>

<script src="views/js/planilla_excepcional.js"></script>

