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

<h2>Gestion de evaluados</h2>
<div class="container py-4">
  <form id="formulario_evaluado" onsubmit="event.preventDefault(); validar_form_evaluado(1);">
  <div class="mb-4 row">
    <div class="col-md-2">
      <label for="id_cedula_usuario" class="form-label">Cédula</label>
      <input class="form-control" type="search" id="id_cedula_usuario" name="cedula_usuario" placeholder="25101172" />
      <button type="button" class="btn btn-primary ms-2" id="btn_buscar_cedula">Buscar</button>
      <small id="mensajeEditarEvaluado" class="form-text text-muted" style="display: none; white-space: nowrap;">Se debe buscar nuevamente los datos del usuario para actualizarlos.</small>
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
  </div>

    <!--<div class="row">
      <div class="col text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>-->
    <div class="row">
  <div class="col-md-12">
    <div class="d-flex justify-content-start">
      <button type="submit" class="btn btn-primary me-2">Guardar</button>
      <button type="button" class="btn btn-warning" id="btnEditarEval" onclick="event.preventDefault(); validar_form_evaluado(2);" disabled>Editar</button>
    </div>
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

<script src="views/js/buscador.js"></script>
