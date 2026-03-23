<?php
include_once '../servicios/Sesion.php';
?>

<div class="container-fluid py-4">
  <!-- Título Principal -->
  <h2 class="mb-4">Reportes de Evaluaciones Obreras</h2>

  <!-- Descripción -->
  <div class="card mb-4">
    <div class="card-body">
      <p class="card-text">
        En esta tabla puede acceder a los reportes en PDF de las evaluaciones realizadas,
        siempre que los campos de comentarios y conformidad estén completos.
      </p>
    </div>
  </div>

  <!-- Formulario de Filtros -->
  <div class="card mb-4">
    <div class="card-header bg-light">
      <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h6>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-bold">Cédula</label>
          <input type="text" class="form-control" id="filtroCedulaObrero" placeholder="Ej: 12345678">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Nombre Completo</label>
          <input type="text" class="form-control" id="filtroNombreObrero" placeholder="Ej: Juan Pérez">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Año</label>
          <input type="text" class="form-control" id="filtroAnioObrero" placeholder="Ej: 2025">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Período</label>
          <input type="text" class="form-control" id="filtroPeriodoObrero" placeholder="Ej: Enero-Marzo">
        </div>
        <div class="col-md-2">
          <div class="d-grid gap-2 d-md-block">
            <button type="button" class="btn btn-primary w-100 mb-2" id="btn_buscar_reportes_obrero">
              <i class="fas fa-search me-1"></i>Buscar
            </button>
            <button type="button" class="btn btn-outline-secondary w-100" id="btn_limpiar_reportes_obrero">
              <i class="fas fa-times me-1"></i>Limpiar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla de Reportes -->
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h6 class="mb-0"><i class="fas fa-table me-2"></i>Listado de Reportes</h6>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0" id="tabla-reportes-obrero">
          <thead class="table-dark">
            <tr>
              <th>Cédula</th>
              <th>Apellidos y nombres</th>
              <th>Cargo</th>
              <th>Año</th>
              <th>Período</th>
              <th>Conformidad</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
