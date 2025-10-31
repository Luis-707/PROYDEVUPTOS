<?php
// Validar sesión
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

<h2>Gestión de Evaluados</h2>
<div class="container py-4">
  <form id="formulario_Gevaluado" onsubmit="event.preventDefault(); validar_form_evaluado(1);">

    <!-- Cédula -->
    <div class="mb-4 row">
      <div class="col-md-4 d-flex">
        <input class="form-control" type="search" id="id_cedula_evaluado" name="cedula_evaluado" placeholder="Cédula" />
        <button type="button" class="btn btn-primary ms-2" id="btn_buscar_cedula_eval">Buscar</button>
      </div>
    </div>

    <!-- Nombre completo -->
    <div class="mb-4 row">
      <div class="col-md-12">
        <label for="fullname_eval" class="form-label">Apellidos y Nombres</label>
        <input class="form-control" type="text" id="fullname_eval" name="fullname" readonly />
      </div>
    </div>

    <!-- Tipo y Ubicación -->
    <div class="mb-4 row">
      <div class="col-md-6">
        <label for="type_str_eval" class="form-label">Tipo</label>
        <input class="form-control" type="text" id="type_str_eval" name="type_str" readonly />
      </div>
      <div class="col-md-6">
        <label for="additional_eval" class="form-label">Ubicación</label>
        <input class="form-control" type="text" id="additional_eval" name="additional" readonly />
      </div>
    </div>

    <!-- Cargo -->
    <div class="mb-4 row">
     <div class="col-md-6">
      <label for="id_clave" class="form-label">Clave</label>
      <div class="d-flex">
        <input class="form-control" type="password" id="id_clave" name="clave" placeholder="********" />
        <button type="button" class="btn btn-outline-secondary ms-2" id="toggleClave">Mostrar</button>
      </div>
      </div>
   

      <div class="col-md-6">
        <label for="id_cargo_evaluado" class="form-label">Cargo</label>
        <select class="form-select" id="id_cargo_evaluado" name="id_cargo_evaluado">
          <option value="">Seleccione un cargo</option>
        </select>
      </div>
    </div>

    <!-- Botón Guardar -->
    <div class="row">
      <div class="col text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </form>
</div>

<!-- Tabla de Evaluados -->
<table class="table table-bordered align-middle" id="tabla-GestionEvaluados">
  <thead class="table-dark">
    <tr>
      <th>Apellidos y Nombres</th>
      <th>Cédula</th>
      <th>Tipo</th>
      <th>Ubicación</th>
      <th>Cargo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<!-- Modal Editar Evaluado -->
<div class="modal fade" id="modalEditarEvaluado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Editar Evaluado</h5>
      </div>

      <form id="form-modal-editar-evaluado" onsubmit="event.preventDefault(); validar_form_evaluado(2);">
        <div class="modal-body">
          <input type="hidden" id="cedula_modal_eval" name="cedula_evaluado">

          <div class="form-group mb-3">
            <label for="cargo_modal_eval">Cargo</label>
            <select id="cargo_modal_eval" class="form-control" name="id_cargo_evaluado"></select>
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

<!-- Script del buscador -->
<script src="views/js/buscador_evaluados.js"></script>