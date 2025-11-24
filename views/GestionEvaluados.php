<?php
include_once '../servicios/Sesion.php';
?>

<style>
  h2 { font-family: 'Poppins', sans-serif; }
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

<h2>Registro de usuarios evaluados</h2>
<div class="container py-4">
  <form id="formulario_evaluado" onsubmit="event.preventDefault(); validar_form_evaluado(1);">
  <div class="mb-4 row">
    <div class="col-md-2">
      <label for="id_cedula_usuario" class="form-label">Cédula</label>
      <input class="form-control" type="search" id="id_cedula_usuario" name="cedula_usuario" placeholder="25101172" />
      <button type="button" class="btn btn-primary ms-2" id="btn_buscar_cedula">Buscar</button>
    </div>
  </div>

  <div class="mb-4 row">
    <div class="col-md-7">
      <label for="fullname_input" class="form-label">Apellidos y Nombres</label>
      <input class="form-control" type="text" id="fullname_input" name="fullname" placeholder="Pérez Gómez, Juan Carlos" readonly />
    </div>
    <div class="col-md-5">
      <label for="type_str_input" class="form-label">Tipo</label>
      <input class="form-control" type="text" id="type_str_input" name="type_str" placeholder="Empleado" readonly />
    </div>
  </div>

  <div class="row mb-4">
  <div class="col-md-7">
    <label for="additional_input" class="form-label">Ubicación</label>
    <input class="form-control" type="text" id="additional_input" name="additional" placeholder="Oficina de bienes nacionales" readonly />
  </div>

  <div class="col-md-5">
    <label for="id_rol_evaluado" class="form-label">Rol</label>
    <!--<input class="form-control" type="text" placeholder="Evaluado" readonly />-->
    <select class="form-select" id="id_rol_evaluado" name="rol_evaluado">
      <!--<option value="">Seleccione el rol</option>-->
    </select>
  </div>
</div>


  <div class="mb-4 row">
  <div class="col-md-6">
      <label for="id_clave" class="form-label">Clave</label>
      <div class="d-flex flex-column">
        <div class="d-flex">
          <input class="form-control" type="password" id="id_clave" name="clave" placeholder="********" />
          <button type="button" class="btn btn-outline-secondary ms-2" id="toggleClave">Mostrar</button>
        </div>
          <small id="mensajeSeguridad" class="mt-2"></small>
        </div>
    </div>

      <!--<div class="col-md-5">
        <label for="cargo_eval" class="form-label">Cargo</label>
        <select class="form-select" id="id_cargo_evaluado" name="cargo_evaluado">
          <option value="">Seleccione un cargo</option>
        </select>
      </div>-->

    </div>

    <div class="row">
      <div class="col text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </form>
</div>

<table class="table table-bordered align-middle" id="tabla-GestionEvaluados">
  <thead class="table-dark">
    <tr><th>Clave</th><th>Cédula</th><th>Apellidos y nombres</th><th>Estatus</th><th>Acciones</th></tr>
  </thead>
  <tbody></tbody>
</table>

<!-- Modal Editar Evaluado -->
<div class="modal fade" id="modalEditarEvaluado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Editar Evaluado</h5></div>
      <form id="form-modal-editar-evaluado" onsubmit="event.preventDefault(); validar_form_evaluado(2);">
        <div class="modal-body">
          <input type="hidden" id="cedula_modal_eval" name="cedula_usuario">
          <div class="form-group">
              <label for="clave_modal_evaluado">Clave</label>
              <input type="password" value="password" id="clave_modal_evaluado" class="form-control" name="clave" placeholder="Ingrese nueva clave o deje en blanco para no cambiar">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="views/js/buscador.js"></script>
