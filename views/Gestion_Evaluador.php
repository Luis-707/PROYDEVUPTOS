<?php
// Comprobar si la variable de sesión 'usuario' está definida
include_once '../servicios/Sesion.php';
?>

<style>

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

<!--Evaluador-->
<h2>Asignacion de cargos de evaluadores</h2>

<div class="container py-4">
  <button type="button" class="btn btn-primary mb-3" title="Asignar cargo a un evaluador" onclick="abrirModalEditarCargo('boton', '', '')"><i class="bx bx-plus"></i></button>
</div>

<div class="modal fade" id="modalEditarCargo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Cargo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="form-modal-editar-cargo" onsubmit="event.preventDefault(); validar_formEvaluador(2);">
        <div class="modal-body">
          <input type="hidden" id="id_usuario_modal" name="id_usuario">
          <div class="mb-3" id="div_id_usuario" style="display: none;">
            <label for="id_usuario" class="form-label">Seleccionar Evaluador</label>
            <select class="form-select" id="id_usuario_select" name="id_usuario">
              <option selected disabled>Seleccione un evaluador</option>
            </select>
          </div>
          <div class="mb-3" id="div_id_supervisor" style="display: none;">
            <label for="id_supervisor" class="form-label">Asignar Supervisor</label>
            <select class="form-select" id="id_supervisor" name="id_supervisor">
              <option selected disabled>Seleccione un supervisor</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="cargo_modal">Cargo:</label>
            <select id="cargo_modal" name="id_cargo_evaluador" class="form-select"></select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" form="form-modal-editar-cargo" class="btn btn-primary">Guardar Cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<table class="table table-bordered align-middle" id="tabla-evaluadores">
  <thead class="table-dark">
    <tr>
      <th>Cedula</th>
      <th>Apellidos y nombres</th>
      <th>Ubicacion</th>
      <th>Cargo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
