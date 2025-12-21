<?php
// Comprobar si la variable de sesión 'usuario' está definida
include_once '../servicios/Sesion.php';
?>

<style>
    /* Poner Poppins solo para el título h2 */
    h2 {
      font-family: 'Poppins', sans-serif;
    }
    /* Poner Calibri para todo el resto del texto con tamaño 14px */
    body, div, form, label, input, button, select, table, th, td {
      font-family: 'Calibri', sans-serif;
      font-size: 14px;
    }

    /* Columna de acciones */
td.acciones {
  text-align: center;
  vertical-align: middle;
  white-space: nowrap;
}

/* Contenedor de íconos */
.acciones-icons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px; /* espacio uniforme entre íconos */
}

/* Íconos */
.acciones-icons img {
  width: 22px;
  cursor: pointer;
}
</style>

<h2>Registro de datos para las evaluaciones de personal administrativo</h2>
<div class="container py-4">
      <form id="formulario_EvalAdmin" onsubmit="event.preventDefault(); validar_formEvalAdmin(1);">
        <div class="col-md-4">
          <label for="id_evaluado" class="form-label">Seleccionar evaluado</label>
          <select class="form-select" id="id_evaluado" name="id_evaluado" required>
            <option selected disabled>Seleccione un evaluado</option>
          </select>
        </div>

        <div class="col-md-4">
          <label for="periodo_evaluado" class="form-label">Periodo</label>
          <select class="form-select" id="periodo_evaluado" name="periodo_evaluado" required onchange="ajustarFechasPeriodo(this.value)">
            <option selected disabled>Seleccione un periodo</option>
            <option value="Enero-Junio">Enero-Junio</option>
            <option value="Julio-Diciembre">Julio-Diciembre</option>
          </select>
        </div>
      
        <div class="col-md-4">
          <label for="fecha_inicio" class="form-label">fecha_inicio</label>
          <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" readonly />
        </div>
      
        <div class="col-md-4">
          <label for="fecha_cierre" class="form-label">fecha_cierre</label>
          <input class="form-control" type="date" id="fecha_cierre" name="fecha_cierre" readonly />
        </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
      </form>
</div>

<table class="table table-bordered align-middle" id="tabla-EvalAdmin">
    <thead class="table-dark">
        <tr><th>Cedula</th><th>Apellidos y nombres</th><th>Ubicacion</th><th>Cargo</th><th>Año</th><th>Periodo</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal Objetivos -->
<div class="modal fade" id="modalObjetivos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Objetivos disponibles</h5>
        <!-- Botón de cierre correcto en BS5 -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="contenedor-switches-objetivos">
        <!-- Aquí se inyectan los switches -->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal para actualizar periodo y fechas -->
<div class="modal fade" id="modalEditarPeriodo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Actualizar Periodo de Evaluación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="form-modal-editar-periodo" onsubmit="event.preventDefault(); actualizarPeriodoEvaluacion(2);">
        <div class="modal-body">
          <!-- Hidden id_eval_admin -->
          <input type="hidden" id="id_eval_admin_modal" name="id_eval_admin">

          <!-- Periodo -->
          <div class="mb-3">
            <label for="periodo_evaluado_modal" class="form-label">Periodo</label>
            <select class="form-select" id="periodo_evaluado_modal" name="periodo_evaluado" required onchange="ajustarFechasPeriodoModal(this.value)">
              <option selected disabled>Seleccione un periodo</option>
              <option value="Enero-Junio">Enero-Junio</option>
              <option value="Julio-Diciembre">Julio-Diciembre</option>
            </select>
          </div>

          <!-- Fechas (solo cambia el año) -->
          <div class="row">
            <div class="col-md-6">
              <label for="fecha_inicio_modal" class="form-label">Fecha inicio</label>
              <input class="form-control" type="number" id="fecha_inicio_modal" name="fecha_inicio" min="2000" max="2100" required />
              <small class="text-muted">Solo año (ej: 2025)</small>
            </div>
            <div class="col-md-6">
              <label for="fecha_cierre_modal" class="form-label">Fecha cierre</label>
              <input class="form-control" type="number" id="fecha_cierre_modal" name="fecha_cierre" min="2000" max="2100" required />
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