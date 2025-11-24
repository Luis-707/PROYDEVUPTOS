<?php
include_once "sesion_start.php";
?>

<!--<label for="id_evaluador">Seleccionar usuario:</label>
    <select id="id_evaluador" name="id_evaluador">
    <option value="">Seleccione a un usuario</option>
</select>

<label for="id_cargo_evaluador">Seleccionar cargo:</label>
    <select id="id_cargo_evaluador" name="id_cargo_evaluador">
    <option value="">Seleccione a un cargo</option>
</select>

<table class="table table-bordered align-middle" id="tabla-evaluadores">
    <thead class="table-dark">
        <tr><th>Apellidos y nombres</th><th>Cédula Usuario</th><th>Cargo</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>-->

<!--<form id="formulario_evaluador" onsubmit="event.preventDefault(); validar_formEvaluador(1);">

<label for="id_usuario">Usuario:</label>
    <select id="id_usuario" name="id_usuario">
    <option value="">Seleccione a un usuario</option>
</select>
<label for="id_cargo_evaluador">Seleccionar cargo:</label>
    <select id="id_cargo_evaluador" name="id_cargo_evaluador">
    <option value="">Seleccione a un cargo</option>
</select>

<label for="id_supervisor">Seleccionar supervisor:</label>
    <select id="id_supervisor" name="id_supervisor">
    <option value="">Seleccione a un supervisor</option>
</select>


<button type="submit">Guardar</button>

</form>-->

<!--Evaluador-->
<h2>Registro de datos de evaluadores</h2>
<div class="container py-4">
      <form id="formulario_evaluador" onsubmit="event.preventDefault(); validar_formEvaluador(1);">
        <div class="col-md-4">
          <label for="id_usuario" class="form-label">Seleccionar Evaluador</label>
          <select class="form-select" id="id_usuario" name="id_usuario">
            <option selected disabled>Seleccione un evaluador</option>
          </select>
        </div>
      
        <div class="col-md-4">
          <label for="id_cargo_evaluador" class="form-label">Seleccionar Cargo</label>
          <select class="form-select" id="id_cargo_evaluador" name="id_cargo_evaluador">
            <option selected disabled>Seleccione un cargo</option>
          </select>
        </div>
      
        <div class="col-md-4">
          <label for="id_supervisor" class="form-label">Asignar Supervisor</label>
          <select class="form-select" id="id_supervisor" name="id_supervisor">
            <option selected disabled>Seleccione un supervisor</option>
          </select>
        </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
      </form>
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
          <label for="cargo_modal">Cargo:</label>
          <select id="cargo_modal" name="id_cargo_evaluador" class="form-select"></select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-modal-editar-cargo" class="btn btn-primary">Guardar Cambios</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> 


<table class="table table-bordered align-middle" id="tabla-evaluadores">
    <thead class="table-dark">
        <tr><th>Apellidos y Nombres</th><th>Cédula </th><th>Cargo</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>
