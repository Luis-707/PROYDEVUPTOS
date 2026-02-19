<?php include_once "Sesion.php"; ?>

<div class="container mt-4">
  <h3>EVALUACIÓN DEL DESEMPEÑO</h3>
  <h5>NIVEL ADMINISTRATIVO (Solo Lectura)</h5>

  <button class="btn btn-outline-primary btn-sm"
          onclick="mostrarVista('comentarios'); listarEvaluadosComentarios();">
      ← Volver al listado
    </button>

  <!-- Sección A: Datos de identificación -->
  <div class="row mt-5">
    <!-- Evaluado -->
    <div class="col-md-4">
      <h5>Evaluado</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluado_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluado_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluado_cargo"></span></li>
        <li class="list-group-item"><strong>Unidad:</strong> <span id="evaluado_ubicacion"></span></li>
      </ul>
    </div>

    <!-- Evaluador -->
    <div class="col-md-4">
      <h5>Evaluador</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluador_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluador_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluador_cargo"></span></li>
        <li class="list-group-item"><strong>Unidad:</strong> <span id="evaluador_ubicacion"></span></li>
      </ul>
    </div>

    <!-- Supervisor -->
    <div class="col-md-4">
      <h5>Supervisor</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="supervisor_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="supervisor_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="supervisor_cargo"></span></li>
        <li class="list-group-item"><strong>Unidad:</strong> <span id="supervisor_ubicacion"></span></li>
      </ul>
    </div>
  </div>

  <!-- Sección B: Objetivos -->
  <div class="row mt-5">
    <div class="col-md-12">
      <h6 class="text-muted">SECCIÓN "B": OBJETIVOS DE DESEMPEÑO</h6>
      <table class="table table-bordered" id="tabla-objetivos-readonly">
        <thead>
          <tr><th>Nombre</th><th>Peso</th><th>Rango</th><th>Peso x Rango</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Sección C: Competencias -->
  <div class="row mt-5">
    <div class="col-md-12">
      <h6 class="text-muted">SECCIÓN "C": COMPETENCIAS</h6>
      <table class="table table-bordered" id="tabla-competencias-readonly">
        <thead>
          <tr><th>Nombre</th><th>Peso</th><th>Rango</th><th>Peso x Rango</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Sección D: Resultado final -->
  <div class="row mt-5">
    <div class="col-md-6 offset-md-3">
      <h6 class="text-muted">SECCIÓN "D": RESULTADO FINAL</h6>
      <table class="table table-bordered text-center">
        <thead>
          <tr><th>Puntaje Final</th><th>Rango de Actuación</th></tr>
        </thead>
        <tbody>
          <tr>
            <td id="puntaje-total"></td>
            <td id="rango-actuacion"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sección E: Comentarios -->
  <div class="row mt-4">
    <div class="col-md-12">
      <h6 class="text-muted d-flex justify-content-between">
        <span>SECCIÓN "E":</span>
        <span>COMENTARIOS</span>
      </h6>
    </div>

    <!-- Formulario Evaluado -->
    <form id="form_comentario_evaluado" onsubmit="event.preventDefault(); Validar_form_comentario_evaluado(1);">
      <textarea id="comentario_evaluado" name="comentario_evaluado" class="form-control" rows="4"></textarea>

      <div class="mb-3">
        <label class="form-label">¿Está de acuerdo con la evaluación?</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="conformidad" id="conformidad_si" value="si">
          <label class="form-check-label" for="conformidad_si">Sí</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="conformidad" id="conformidad_no" value="no">
          <label class="form-check-label" for="conformidad_no">No</label>
        </div>
      </div>

      <input type="hidden" id="id_eval_admin_eval" name="id_eval_admin" value="">
      <button type="submit" class="btn btn-primary">Guardar Comentario Evaluado</button>
    </form>

    <!-- Formulario Supervisor -->
    <form id="form_comentario_supervisor" onsubmit="event.preventDefault(); Validar_form_comentario_supervisor(1);">
      <textarea id="comentario_supervisor" name="comentario_supervisor" class="form-control" rows="4"></textarea>
      <input type="hidden" id="id_eval_admin_sup" name="id_eval_admin" value="">
      <button type="submit" class="btn btn-primary">Guardar Comentario Supervisor</button>
    </form>
  </div>
</div>



<script src="views/js/planilla_comentarios.js"></script>


