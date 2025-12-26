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

<h2>Registro de usuarios en el sistema</h2>
<div class="container py-4">
    <form id="formulario_usuario" onsubmit="event.preventDefault(); validar_form(1);">
    <!-- Campo oculto: cédula -->
    <!--<input type="hidden" id="cedula_modal" name="cedula_usuario">-->
  <div class="mb-4 row">
    <div class="col-md-2">
      <label for="id_cedula_usuario" class="form-label">Cédula</label>
      <input class="form-control" type="search" id="id_cedula_usuario" name="cedula_usuario" placeholder="25101172" />
      <button type="button" class="btn btn-primary ms-2" id="btn_buscar_cedula">Buscar</button>
      <small id="mensajeEditar" class="form-text text-muted" style="display: none; white-space: nowrap;">Se debe buscar nuevamente los datos del usuario para actualizarlos.</small>
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

  <div class="mb-4 row">
    <div class="col-md-12">
      <label for="additional_input" class="form-label">Ubicación</label>
      <input class="form-control" type="text" id="additional_input" name="additional" placeholder="Oficina de bienes nacionales" readonly />
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
    <div class="col-md-6">
      <label for="id_rol_sistema" class="form-label">Rol</label>
      <select class="form-select" id="id_rol_sistema" aria-label="Selección de rol">
        <option selected>Seleccione un rol</option>
      </select>
    </div>
  </div>

  <!--<div class="row">
    <div class="col text-center">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </div>

  <div class="mb-4 row">
  <div class="col-md-12 text-end">
    <button type="button" class="btn btn-warning" id="btnEditar" disabled>Editar</button>
  </div>
  </div>-->

  <div class="row">
  <div class="col-md-12">
    <div class="d-flex justify-content-start">
      <button type="submit" class="btn btn-primary me-2">Guardar</button>
      <button type="button" class="btn btn-warning" id="btnEditar" onclick="event.preventDefault(); validar_form(2);" disabled>Editar</button>
    </div>
  </div>
  </div>


</form>



    </div>
<table class="table table-bordered align-middle" id="tabla-usuarios">
    <thead class="table-dark">
        <tr><th>Clave</th><th>Cédula Usuario</th><th>Apellidos y nombres</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal Permisos -->
<div class="modal fade" id="modalPermisos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Permisos del usuario</h5>
        <!-- Botón de cierre correcto en BS5 -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="contenedor-switches-permisos">
        <!-- Aquí se inyectan los switches -->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script src="views/js/buscador.js"></script>






    
