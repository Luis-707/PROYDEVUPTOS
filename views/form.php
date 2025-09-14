<!--<form id="formulario_usuario" onsubmit="event.preventDefault(); validar_form(1);">
    <label for="id_clave">Clave:</label>
    <input type="text" id="id_clave" name="clave" required />

    <label for="id_cedula_usuario">Cédula Usuario (solo números):</label>
    <input type="text" id="id_cedula_usuario" name="cedula_usuario" required />

    <select id="id_cargo_supervisor" name="cargo_supervisor">

    </select>

    <select id="id_jefe_superior" name="jefe_superior">

    </select>

    <button type="submit">Guardar</button>
</form>


<table id="tabla-usuarios">
    <thead>
        <tr><th>Clave</th><th>Cédula Usuario</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>
-->

<form id="formulario_usuario" onsubmit="event.preventDefault(); validar_form(1);">

    <label for="search_input">Buscar empleado:</label>
    <input type="text" id="search_input" name="search" placeholder="Buscar por cédula o nombre..." />

    <label for="id_clave">Clave:</label>
    <input type="text" id="id_clave" name="clave" required />

    <label for="id_cedula_usuario">Cédula Usuario (solo números):</label>
    <input type="text" id="id_cedula_usuario" name="cedula_usuario" required />

    <label for="fullname_input">Nombre completo:</label>
    <input type="text" id="fullname_input" name="fullname" readonly />

    <label for="type_str_input">Tipo:</label>
    <input type="text" id="type_str_input" name="type_str" readonly />

    <label for="additional_input">Información adicional:</label>
    <input type="text" id="additional_input" name="additional" readonly />

    <label for="id_rol_sistema">Rol:</label>
    <select id="id_rol_sistema" name="Roles">
    <option value="">Seleccione un Rol de sistema</option>
    </select>

    <button type="submit">Guardar</button>

</form>

<table class="table table-bordered align-middle" id="tabla-usuarios">
    <thead class="table-dark">
        <tr><th>Clave</th><th>Cédula Usuario</th><th>Apellidos y nombres</th><th>Rol</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="form-modal-editar" onsubmit="event.preventDefault(); validar_form(2);">
          <div class="modal-body">
            <!-- Campo oculto: cédula -->
            <input type="hidden" id="cedula_modal" name="cedula_usuario">

            <div class="form-group">
              <label for="clave_modal">Clave</label>
              <input type="text" id="clave_modal" name="clave" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="rol_modal">Rol del Sistema</label>
              <select id="rol_modal" class="form-control" name="rol_id"></select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
      </form>
     
    </div>
  </div>
</div>


<script src="views/js/buscador.js"></script>





    
