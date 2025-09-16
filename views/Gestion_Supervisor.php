<form id="formulario_supervisor" onsubmit="event.preventDefault(); validar_formSupervisor(1);">

<label for="id_usuario">Usuario:</label>
    <select id="id_usuario" name="id_usuario">
    <option value="">Seleccione a un usuario</option>
</select><!-- Buscar que se capte el id_usuario en el js para que se guarde junto al cargo -->

<label for="id_cargo_supervisor">Seleccionar cargo:</label>
    <select id="id_cargo_supervisor" name="id_cargo_supervisor">
    <option value="">Seleccione a un cargo</option>
</select>

<button type="submit">Guardar</button>

</form>

<div class="modal fade" id="modalEditarCargoSuperv" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Cargo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="form-modal-editar-cargo-superv" onsubmit="event.preventDefault(); validar_formSupervisor(2);">
      <div class="modal-body">

          <input type="hidden" id="id_usuario_modal" name="id_usuario">
          <label for="cargosuperv_modal">Cargo:</label>
          <select id="cargosuperv_modal" name="id_cargo_supervisor" class="form-select"></select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-modal-editar-cargo-superv" class="btn btn-primary">Guardar Cambios</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> 


<table class="table table-bordered align-middle" id="tabla-supervisores">
    <thead class="table-dark">
        <tr><th>Apellidos y Nombres</th><th>Cédula </th><th>Cargo</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>
