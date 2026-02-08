<?php
// Comprobar si la variable de sesión 'usuario' está definida
include_once '../servicios/Sesion.php';
?>

<style>
    h2 {
      font-family: 'Poppins', sans-serif;
    }

    body, div, form, label, input, button, select, table, th, td {
      font-family: 'Calibri', sans-serif;
      font-size: 14px;
    }

    td.acciones {
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;
    }

    .acciones-icons {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
    }

    .acciones-icons img {
      width: 22px;
      cursor: pointer;
    }
</style>

<h2>Registro de datos para las evaluaciones de personal obrero</h2>

<div class="container py-4">
    <form id="formulario_EvalObrero" onsubmit="event.preventDefault(); validar_formEvalObrero(1);">

        <div class="col-md-4">
          <label for="id_evaluado_obrero" class="form-label">Seleccionar evaluado</label>
          <select class="form-select" id="id_evaluado_obrero" name="id_evaluado" required>
            <option selected disabled>Seleccione un evaluado</option>
          </select>
        </div>

        <div class="col-md-4">
          <label for="periodo_evaluacion" class="form-label">Periodo</label>
          <select class="form-select" id="periodo_evaluacion" name="periodo_evaluacion" required onchange="ajustarFechasPeriodoObrero(this.value)">
            <option selected disabled>Seleccione un periodo</option>
            <option value="Enero-Junio">Enero-Junio</option>
            <option value="Julio-Diciembre">Julio-Diciembre</option>
          </select>
        </div>

        <div class="col-md-4">
          <label for="fecha_inicio_obrero" class="form-label">Fecha inicio</label>
          <input class="form-control" type="date" id="fecha_inicio_obrero" name="fecha_inicio" readonly />
        </div>

        <div class="col-md-4">
          <label for="fecha_cierre_obrero" class="form-label">Fecha cierre</label>
          <input class="form-control" type="date" id="fecha_cierre_obrero" name="fecha_cierre" readonly />
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>
</div>

<table class="table table-bordered align-middle" id="tabla-EvalObrero">
    <thead class="table-dark">
        <tr>
            <th>Cédula</th>
            <th>Apellidos y nombres</th>
            <th>Ubicación</th>
            <th>Cargo</th>
            <th>Año</th>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal para actualizar periodo y fechas -->
<div class="modal fade" id="modalEditarPeriodoObrero" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Actualizar Periodo de Evaluación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="form-modal-editar-periodo-obrero" onsubmit="event.preventDefault(); actualizarPeriodoEvaluacionObrero(2);">
        <div class="modal-body">

          <input type="hidden" id="id_eval_obreros_modal" name="id_eval_obreros">

          <div class="mb-3">
            <label for="periodo_evaluacion_modal" class="form-label">Periodo</label>
            <select class="form-select" id="periodo_evaluacion_modal" name="periodo_evaluacion" required onchange="ajustarFechasPeriodoModalObrero(this.value)">
              <option selected disabled>Seleccione un periodo</option>
              <option value="Enero-Junio">Enero-Junio</option>
              <option value="Julio-Diciembre">Julio-Diciembre</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="fecha_inicio_modal_obrero" class="form-label">Fecha inicio</label>
              <input class="form-control" type="number" id="fecha_inicio_modal_obrero" name="fecha_inicio" min="2000" max="2100" required />
              <small class="text-muted">Solo año (ej: 2025)</small>
            </div>

            <div class="col-md-6">
              <label for="fecha_cierre_modal_obrero" class="form-label">Fecha cierre</label>
              <input class="form-control" type="number" id="fecha_cierre_modal_obrero" name="fecha_cierre" min="2000" max="2100" required />
              <small class="text-muted">Solo año (ej: 2025)</small>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>

      </form>
    </div>
  </div>
</div>
