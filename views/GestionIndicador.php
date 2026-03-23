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

<h2>Gestion de indicadores para evaluaciones de desempeño excepcional</h2>

<button type="button" class="btn btn-primary mb-3" title="Agregar nuevo indicador" onclick="abrirModalIndicador()"><i class="bx bx-plus"></i></button>

<table class="table table-bordered align-middle" id="tabla-indic">
    <thead class="table-dark">
        <tr><th>Indicador</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal HTML (puedes colocarlo en tu HTML general) -->
<div class="modal fade" id="modalIndicador" tabindex="-1" aria-labelledby="modalIndicadorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="form-modal-Indicador" class="modal-content" onsubmit="event.preventDefault(); validar_form_indicador(2);">
      <div class="modal-header">
        <h5 class="modal-title" id="modalIndicadorLabel">-Titulo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="indicador_id_modal" name="indicador_id">
        <div class="mb-3">
          <label for="indicador_modal" class="form-label">Ingrese un indicador</label>
          <input type="text" class="form-control" id="indicador_modal" name="indicador" placeholder="Ej: Trabajo adicional">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>




