<?php
// Comprobar si la variable de sesión 'usuario' está definida
include_once '../servicios/Sesion.php';
?>

<style>
    /* Poner Poppins solo para el título h2 */
    h2 {
      font-family: 'Poppins', sans-serif;
    }
    /* Poner Calibri para todo el resto del texto con tamaño 14px */
    body, div, form, label, input, button, select, table, th, td {
      font-family: 'Calibri', sans-serif;
      font-size: 14px;
    }

    /* Columna de acciones */
td.acciones {
  text-align: center;
  vertical-align: middle;
  white-space: nowrap;
}

/* Contenedor de íconos */
.acciones-icons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px; /* espacio uniforme entre íconos */
}

/* Íconos */
.acciones-icons img {
  width: 22px;
  cursor: pointer;
}
</style>

<h2>Registro de evaluaciónes administrativas</h2>
<div class="container py-4">
  <form id="formulario_EvalAdmin" onsubmit="event.preventDefault(); validar_formEvalAdmin(1);">
    
    <!-- Evaluado -->
    <div class="mb-3">
      <label for="id_evaluado" class="form-label">Seleccionar evaluado</label>
      <select class="form-select" id="id_evaluado" name="id_evaluado" required>
        <option selected disabled>Seleccione un evaluado</option>
      </select>
    </div>

    <!-- Periodo -->
    <div class="mb-3">
      <label for="periodo_evaluado" class="form-label">Periodo</label>
      <select class="form-select" id="periodo_evaluado" name="periodo_evaluado" required onchange="ajustarFechasPeriodo(this.value)">
        <option selected disabled>Seleccione un periodo</option>
        <option value="Enero-Junio">Enero-Junio</option>
        <option value="Julio-Diciembre">Julio-Diciembre</option>
      </select>
    </div>

    <!-- Fechas (rellenadas automáticamente según periodo) -->
    <div class="row">
      <div class="col-md-6">
        <label for="fecha_inicio" class="form-label">Fecha inicio</label>
        <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" readonly />
      </div>
      <div class="col-md-6">
        <label for="fecha_cierre" class="form-label">Fecha cierre</label>
        <input class="form-control" type="date" id="fecha_cierre" name="fecha_cierre" readonly />
      </div>
    </div>

    <div class="mt-3 text-center">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>

<table class="table table-bordered align-middle" id="tabla-EvalAdmin">
    <thead class="table-dark">
        <tr><th>Cedula</th><th>Apellidos y nombres</th><th>Ubicacion</th><th>Cargo</th><th>Periodo</th><th>Estatus</th></tr>
    </thead>
    <tbody></tbody>
</table>