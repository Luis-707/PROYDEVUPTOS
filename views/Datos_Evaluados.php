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

<h2>Registro de datos de supervisores</h2>

<div class="container py-4">
      <form id="formulario_DatosEvaluado" onsubmit="event.preventDefault(); validar_formDatosEvaluado(1);">
        <div class="mb-3">
        <input type="hidden" name="id_usuario_sesion" id="id_usuario_sesion" value="">
          <label for="id_usuario" class="form-label">Seleccionar evaluado</label>
          <select class="form-select" id="id_usuario" name="id_usuario">
            <option selected disabled>Seleccione un evaluado</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="id_cargo_evaluado" class="form-label">Seleccionar Cargo</label>
          <select class="form-select" id="id_cargo_evaluado" name="id_cargo_evaluado">
            <option selected disabled>Seleccione un cargo</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </form>
      </div>

<div class="modal fade" id="modalEditarCargoDatosEval" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Cargo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="form-modal-editar-cargo-evaluado" onsubmit="event.preventDefault(); validar_formDatosEvaluado(2);">
      <div class="modal-body">

          <input type="hidden" id="id_usuario_modal" name="id_usuario">
          <label for="cargoEvaluado_modal">Cargo:</label>
          <select id="cargoEvaluado_modal" name="id_cargo_evaluado" class="form-select"></select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-modal-editar-cargo-evaluado" class="btn btn-primary">Guardar Cambios</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> 


<table class="table table-bordered align-middle" id="tabla-DatosEvaluados">
    <thead class="table-dark">
        <tr><th>Apellidos y Nombres</th><th>Cédula </th><th>Cargo</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>