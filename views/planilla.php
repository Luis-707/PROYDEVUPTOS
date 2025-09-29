<div class="container mt-4">
  <h4>Planilla de Evaluación</h4>

  <!-- Periodo y Fechas -->
  <div class="row mt-4">
  <div class="col-md-12">
    <h5>Información de Evaluación</h5>
    <div class="form-group mb-3">
      <label for="fecha-inicio">Fecha Inicio</label>
      <input type="date" id="fecha-inicio" class="form-control">
    </div>
    <div class="form-group mb-3">
      <label for="fecha-cierre">Fecha Cierre</label>
      <input type="date" id="fecha-cierre" class="form-control">
    </div>
    <div class="form-group mb-3" id="periodo-container" style="display:none;">
      <label for="periodo-evaluacion">Periodo de Evaluación</label>
      <select id="periodo-evaluacion" class="form-control"></select>
    </div>
  </div>
</div>

  <!-- Información del Evaluado, Evaluador y Supervisor -->
  <div class="row">
    <!-- Evaluado -->
    <div class="col-md-4">
      <h5>Evaluado</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluado_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluado_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluado_cargo"></span></li>
        <li class="list-group-item"><strong>Ubicación:</strong> <span id="evaluado_ubicacion"></span></li>
      </ul>
    </div>

    <!-- Evaluador -->
    <div class="col-md-4">
      <h5>Evaluador</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluador_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluador_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluador_cargo"></span></li>
        <li class="list-group-item"><strong>Ubicación:</strong> <span id="evaluador_ubicacion"></span></li>
      </ul>
    </div>

    <!-- Supervisor -->
    <div class="col-md-4">
      <h5>Supervisor</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="supervisor_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="supervisor_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="supervisor_cargo"></span></li>
      </ul>
    </div>
  </div>

  <!-- Tablas dinámicas -->
  <div class="row mt-4">
    <div class="col-md-6">
      <h5>Objetivos de Desempeño Individual</h5>
      <table class="table table-bordered" id="tabla-objetivos">
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
            <td id="total-objetivos">0</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="col-md-6">
      <h5>Competencias</h5>
      <table class="table table-bordered" id="tabla-competencias">
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
            <td id="total-competencias">0</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

<!-- Resultado Final -->
<div class="row mt-4">
  <div class="col-md-6 offset-md-3">
    <h5>Resultado Final</h5>
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>Puntaje Final</th>
          <th>Rango de Actuación</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td id="puntaje-total">0</td>
          <td id="rango-actuacion">Aún no ha sido evaluado</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

