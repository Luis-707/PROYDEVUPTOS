<div class="container mt-4">

  <!-- ============================================================
       GRÁFICA 1: DISTRIBUCIÓN POR RANGO DE ACTUACIÓN
  ============================================================ -->
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

  <!-- ============================================================
       GRÁFICA 2: COMPARATIVO SEMESTRAL DE RANGOS
  ============================================================ -->
  <div class="card mb-4">
    <div class="card-header">
      Comparativo semestral de rangos de actuación
    </div>

    <div class="card-body">

      <div class="row mb-3">

        <!-- AÑO ACTUAL -->
        <div class="col-md-4">
          <label class="form-label">Año actual:</label>
          <select id="filtroAnioComparativo" class="form-select"></select>
        </div>

        <!-- PERÍODO -->
        <div class="col-md-4">
          <label class="form-label">Período:</label>
          <select id="filtroPeriodoComparativo" class="form-select">
            <option value="1">1er Semestre (Ene–Jun)</option>
            <option value="2">2do Semestre (Jul–Dic)</option>
          </select>
        </div>

      </div>

      <canvas id="graficaComparativa" height="140"></canvas>

      <hr class="my-4">

      <!-- DESTACADOS -->
      <h5 class="mb-3">Destacados del período</h5>

      <p><strong>Mayor incremento:</strong> <span id="destMayorIncremento">-</span></p>
      <p><strong>Mayor descenso:</strong> <span id="destMayorDescenso">-</span></p>
      <p><strong>Participación total:</strong> <span id="destParticipacion">-</span></p>

    </div>
  </div>

</div>
