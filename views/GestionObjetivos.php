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

<h2>Gestion de objetivos de desempeño individual</h2>

<div class="container py-4">
    <form id="formulario_objetivo" onsubmit="event.preventDefault(); validar_form_objetivos(1);">
        <div class="col-md-4 mb-3">
          <label for="nombre_objetivo" class="form-label">Ingrese un objetivo</label>
            <input class="form-control" type="text" id="nombre_objetivo" name="nombre_objetivo" placeholder="Realizar reportes">
        </div>
        <div class="col-md-4 mb-3">
          <label for="peso_objetivo" class="form-label">Ingrese el peso del objetivo que desea ingresar</label>
            <input class="form-control" type="number" id="peso_objetivo" name="peso_objetivo" placeholder="10">
        </div>
        <button type="submit" class="btn btn-primary mb-3">Guardar</button>
    </form>
</div>



<table class="table table-bordered align-middle" id="tabla-odi">
    <thead class="table-dark">
        <tr><th>Objetivo</th><th>Peso</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal HTML (puedes colocarlo en tu HTML general) -->
<div class="modal fade" id="modalEditarObjetivo" tabindex="-1" aria-labelledby="modalEditarObjetivoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="form-modal-editar-objetivo" class="modal-content" onsubmit="event.preventDefault(); validar_form_objetivos(2);">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarObjetivoLabel">Editar Objetivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_odi_modal" name="id_odi">
        <div class="mb-3">
          <label for="nombre_objetivo_modal" class="form-label">Nombre del Objetivo</label>
          <input type="text" class="form-control" id="nombre_objetivo_modal" name="nombre_objetivo">
        </div>
        <div class="mb-3">
          <label for="peso_objetivo_modal" class="form-label">Peso del Objetivo</label>
          <input type="number" class="form-control" id="peso_objetivo_modal" name="peso_objetivo">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>
