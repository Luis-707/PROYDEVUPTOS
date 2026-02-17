async function listarEvaluacionesObreros() {
  const resp = await microApi('controlador/?listar_evaluaciones_obreros');

  if (!resp || !resp.success) {
      console.error("Error en backend:", resp);
      return;
  }

  const registros = Array.isArray(resp.data) ? resp.data : [];

  if (!Array.isArray(registros)) {
      console.error("El backend no devolvió un array:", registros);
      return;
  }

  if ($.fn.DataTable.isDataTable('#tabla-obreros')) {
      $('#tabla-obreros').DataTable().destroy();
  }

  $('#tabla-obreros tbody').empty();

  const tableData = registros.map(item => {
      return [
          item.cedula_usuario ?? "N/D",
          item.nombre_completo ?? "N/D",
          item.cargo_evaluado ?? "Sin cargo",
          item.anio_inicio ?? "N/A",
          item.periodo_evaluacion ?? "N/A",
          `
          <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" 
                      onclick="abrirPlanillaObrero('${item.cedula_usuario}', '${item.id_eval_obreros}')">
                      <i class="icon-base bx bx-show me-1"></i>Ver planilla
                  </a>
              </div>
          </div>
          `
      ];
  });

  $('#tabla-obreros').DataTable({
      data: tableData,
      columns: [
          { title: "Cédula" },
          { title: "Nombre" },
          { title: "Cargo" },
          { title: "Año" },
          { title: "Período" },
          { title: "Acciones", orderable: false }
      ]
  });
}