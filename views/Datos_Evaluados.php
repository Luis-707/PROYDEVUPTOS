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

<h2>Asignacion de cargos de evaluados</h2>

<div class="container py-4">
<button type="button" class="btn btn-primary mb-3" onclick="abrirModalEditarCargoEvaluado('boton', '')" title="Asignar cargo a un evaluado">
  <i class="bx bx-plus"></i>
</button>

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
          <div class="col-md-4 mb-3" id="div_id_usuario_sesion" style="display: none;">
        <input type="hidden" name="id_usuario_sesion" id="id_usuario_sesion" value="<?php echo $_SESSION['usuario']['id_usuario']; ?>">
          <label for="id_usuario" class="form-label">Seleccionar evaluado</label>
          <select class="form-select" id="id_usuario" name="id_usuario">
            <option selected disabled>Seleccione un evaluado</option>
          </select>
        </div>
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
        <tr><th>Cedula</th><th>Apellidos y nombres</th><th>Ubicacion</th><th>Cargo</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>