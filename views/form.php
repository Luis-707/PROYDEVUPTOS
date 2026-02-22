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

    <button type="button" class="btn btn-primary mb-3" title="Agregar nuevo usuario" onclick="abrirModalUsuario()"><i class="bx bx-plus"></i></button>

<!-- Modal HTML para formulario de usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"> <!-- modal-xl para hacerlo más ancho -->
    <form id="formulario_usuario" class="modal-content" onsubmit="event.preventDefault(); validar_form(1);">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUsuarioLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="max-height: 70vh; overflow-y: auto;"> <!-- Scroll vertical -->
        <!-- Campo oculto: cédula -->
        <!--<input type="hidden" id="cedula_modal" name="cedula_usuario">-->
        <input type="hidden" id="id_usuario_modal" name="id_usuario">
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
            <label for="id_cargo" class="form-label">Cargo</label>
            <select class="form-select" id="id_cargo" aria-label="Selección de cargo">
              <option selected>Seleccione un cargo</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="id_uf" class="form-label">Ubicacion fisica</label>
            <select class="form-select" id="id_uf" aria-label="Selección de ubicacion fisica">
              <option selected>Seleccione una ubicacion fisica</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="fecha_ingreso" class="form-label">fecha de ingreso</label>
            <input class="form-control" type="date" id="fecha_ingreso" name="fecha_ingreso"/>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" id="btnGuardar" class="btn btn-primary me-2">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </form>
  </div>
</div>

<table class="table table-bordered align-middle" id="tabla-usuarios">
    <thead class="table-dark">
        <tr><th>Clave</th><th>Cédula Usuario</th><th>Apellidos y nombres</th><th>Cargo</th><th>Estado</th><th>Acciones</th></tr>
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

<!-- Modal Roles -->
<div class="modal fade" id="modalRoles" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Roles del usuario</h5>
        <!-- Botón de cierre correcto en BS5 -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="contenedor-switches-roles">
        <!-- Aquí se inyectan los switches de roles -->
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando roles...</span>
          </div>
          <p class="mt-2 text-muted">Cargando roles del usuario...</p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script src="views/js/buscador.js"></script>






    