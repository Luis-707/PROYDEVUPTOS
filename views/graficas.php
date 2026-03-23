<div class="container mt-4">

  <div class="card mb-4">
    <div class="card-header">
      Distribución por rango de actuación
    </div>

    <div class="card-body">

      <div class="row mb-3">

        <!-- FILTRO POR AÑO -->
        <div class="col-md-4">
          <label class="form-label">Filtrar por año:</label>
          <select id="filtroAnio" class="form-select">
            <option value="todos">Todos los años</option>
          </select>
        </div>

        <!-- FILTRO POR PERÍODO -->
        <div class="col-md-4">
          <label class="form-label">Filtrar por período:</label>
          <select id="filtroPeriodo" class="form-select">
            <option value="todos">Todos los períodos</option>
          </select>
        </div>

      </div>

      <canvas id="graficaRangos" height="120"></canvas>
      
      <hr class="my-4">
    </div>
  </div>

</div>


