<div class="container mt-4">
  <h4>Planilla de Evaluación</h4>
  <div class="row">
    <div class="col-md-4">
      <h5>Evaluado</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluado_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluado_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluado_cargo"></span></li>
        <li class="list-group-item"><strong>Ubicación:</strong> <span id="evaluado_ubicacion"></span></li>
      </ul>
    </div>
    <div class="col-md-4">
      <h5>Evaluador</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="evaluador_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="evaluador_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="evaluador_cargo"></span></li>
        <li class="list-group-item"><strong>Ubicación:</strong> <span id="evaluador_ubicacion"></span></li>
      </ul>
    </div>
    <div class="col-md-4">
      <h5>Supervisor</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong> <span id="supervisor_fullname"></span></li>
        <li class="list-group-item"><strong>Cédula:</strong> <span id="supervisor_cedula"></span></li>
        <li class="list-group-item"><strong>Cargo:</strong> <span id="supervisor_cargo"></span></li>
      </ul>
    </div>
  </div>
</div>


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

<div class="row mt-3">
  <div class="col-md-12 text-end">
    <h5>Total General: <span id="total-general">0</span></h5>
  </div>
</div>

<!-- <script src="views/js/planilla.js"></script>

<script src="views/js/planilla.js"></script> -->