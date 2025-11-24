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

<h2>Gestion de competencias</h2>

<div class="container py-4">
    <form id="formulario_competencia" onsubmit="event.preventDefault(); validar_form_competencia(1);">
        <div class="col-md-4 mb-3">
          <label for="nombre_competencia" class="form-label">Ingrese una competencia</label>
            <input class="form-control" type="text" id="nombre_competencia" name="nombre_competencia" placeholder="Eficiencia laboral">
        </div>
        <div class="col-md-4 mb-3">
          <label for="peso_competencia" class="form-label">Ingrese el peso de la competencia que desea ingresar</label>
            <input class="form-control" type="number" id="peso_competencia" name="peso_competencia" placeholder="10">
        </div>

        <div class="col-md-4 mb-3">
          <label for="estado_competencia" class="form-label">Seleccionar periodo a evaluar</label>
          <select class="form-select" id="estado_competencia" name="estado_competencia">
            <option selected disabled>Seleccione un estado</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
        </div>

        <button type="submit" class="btn btn-primary mb-3">Guardar</button>
    </form>
</div>



<table class="table table-bordered align-middle" id="tabla-comp">
    <thead class="table-dark">
        <tr><th>Competencia</th><th>Peso</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal HTML (puedes colocarlo en tu HTML general) -->
<div class="modal fade" id="modalEditarCompetencia" tabindex="-1" aria-labelledby="modalEditarCompetenciaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="form-modal-editar-competencia" class="modal-content" onsubmit="event.preventDefault(); validar_form_competencia(2);">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarCompetenciaLabel">Editar Objetivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_competencia_modal" name="id_competencia">
        <div class="mb-3">
          <label for="nombre_competencia_modal" class="form-label">Nombre de la competencia</label>
          <input type="text" class="form-control" id="nombre_competencia_modal" name="nombre_competencia">
        </div>
        <div class="mb-3">
          <label for="peso_competencia_modal" class="form-label">Peso de la competencia</label>
          <input type="number" class="form-control" id="peso_competencia_modal" name="peso_competencia">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>
