<style>


  .table-bordered th, .table-bordered td {
    border: 1px solid #dee2e6;
    
  }

</style>

<div class="container mt-4">
  <h3>EDITAR EVALUACION DEL DESEMPEÑO</h3>
  <h5>NIVEL ADMINISTRATIVO</h5>

  <form id="formulario_planilla_editar" onsubmit="event.preventDefault(); validar_form_editar_evaluacion(1);">

    <!-- Periodo y Fechas -->
    <div class="row mt-4">
      <div class="col-md-8">
        <div class="form-group col-md-4 mb-3">
          <label for="fecha-inicio">Fecha Inicio</label>
          <input type="text" id="fecha-inicio" name="fecha_inicio" class="form-control" readonly>
        </div>
        <div class="form-group col-md-4 mb-3">
          <label for="fecha-cierre">Fecha Cierre</label>
          <input type="text" id="fecha-cierre" name="fecha_cierre" class="form-control" readonly>
        </div>
        <div class="form-group col-md-4 mb-3">
          <label for="periodo-evaluacion">Periodo de Evaluación</label>
          <input type="text" id="periodo-evaluacion" name="periodo_evaluado" class="form-control" readonly>
        </div>
      </div>
    </div>

    <!-- Información del Evaluado, Evaluador y Supervisor -->
    <div class="row mt-5">
      <div class="col-md-12">
      <h6 class="text-muted d-flex justify-content-between">
  <span>SECCIÓN "A":</span>
  <span>DATOS DE IDENTIFICACIÓN</span>
</h6>
      </div>
      <!-- Evaluado -->
      <div class="col-md-4 mt-3">
        <h5>Evaluado</h5>
        <ul class="list-group">
          <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluado_fullname"></span></li>
          <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluado_cedula"></span></li>
          <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluado_cargo"></span></li>
          <li class="list-group-item"><strong>Ubicación:</strong> <span id="evaluado_ubicacion"></span></li>
        </ul>
        <input type="hidden" id="id_evaluado" name="id_evaluado">
      </div>

      <!-- Evaluador -->
      <div class="col-md-4 mt-3">
        <h5>Evaluador</h5>
        <ul class="list-group">
          <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluador_fullname"></span></li>
          <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluador_cedula"></span></li>
          <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluador_cargo"></span></li>
          <li class="list-group-item"><strong>Ubicación:</strong> <span id="evaluador_ubicacion"></span></li>
        </ul>
        <input type="hidden" id="id_usuario" name="id_usuario">
      </div>

      <!-- Supervisor -->
      <div class="col-md-4 mt-3">
        <h5>Supervisor</h5>
        <ul class="list-group">
          <li class="list-group-item"><strong>Nombre:</strong> <span id="supervisor_fullname"></span></li>
          <li class="list-group-item"><strong>Cédula:</strong> <span id="supervisor_cedula"></span></li>
          <li class="list-group-item"><strong>Cargo:</strong> <span id="supervisor_cargo"></span></li>
        </ul>
      </div>
    </div>

    <!-- Tablas dinámicas -->
    <div class="row mt-4 justify-content-center">
      <div class="col-md-8 offset-md-2">
      <h6 class="text-muted d-flex justify-content-between">
  <span>SECCIÓN "B":</span>
  <span>ESTABLECIMIENTO Y EVALUACION DE OBJETIVOS DE DESEMPEÑO INDIVIDUAL</span>
</h6>
        <table class="table table-bordered mb-0" id="tabla-objetivos-editar">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Peso</th>
              <th>Rango</th>
              <th>Peso x Rango</th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan="3"><strong>Total</strong></td>
              <td id="total-objetivos-editar">0</td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="col-md-8 mt-5 offset-md-2">
      <h6 class="text-muted d-flex justify-content-between">
  <span>SECCIÓN "C":</span>
  <span>EVALUACION DE LAS COMPETENCIAS</span>
</h6>
        <table class="table table-bordered mb-0" id="tabla-competencias-editar">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Peso</th>
              <th>Rango</th>
              <th>Peso x Rango</th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan="3"><strong>Total</strong></td>
              <td id="total-competencias-editar">0</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Resultado Final -->
    <div class="row mt-5">
      <div class="col-md-6 offset-md-3">
      <h6 class="text-muted d-flex justify-content-between">
  <span>SECCIÓN "D":</span>
  <span>RANGOS DE ACTUACION DEL EVALUADO</span>
</h6>
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th>Puntaje Final</th>
              <th>Rango de Actuación</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="puntaje-total-editar">0</td>
              <td id="rango-actuacion-editar">Aún no ha sido evaluado</td>
            </tr>
          </tbody>
        </table>
        <!--<input type="hidden" id="id_eval_admin" name="id_eval_admin" value="">-->
        <input type="hidden" id="id_rango" name="id_rango" value="0">
        <input type="hidden" id="puntaje_final" name="puntaje_final">
      </div>
    </div>


    <!-- Botón Actualizar -->
    <div class="row mt-5">
      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-warning">Actualizar Evaluación</button>
      </div>
    </div>
  </form>
</div>

<script src="views/js/planilla_editar.js"></script>