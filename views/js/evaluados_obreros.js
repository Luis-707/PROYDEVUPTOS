// Listar evaluaciones de obreros (misma lógica que listarEvaluados en administrativos)
async function listarEvaluacionesObreros() {
  const datos = await obtenerDatosPersonalesObreros();
  if (!datos) return;

  if ($.fn.DataTable.isDataTable('#tabla-obreros')) {
      $('#tabla-obreros').DataTable().destroy();
  }

  $('#tabla-obreros tbody').empty();

  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  const tableData = registros.map(item => {
      const cedula = String(item.cedula_usuario).trim();
      const fullname = item.nombre_completo || "No encontrado";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const periodoEvaluacion = item.periodo_evaluacion || "N/A";
      const anioInicio = item.anio_inicio || "N/A";

      const acciones = `
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0);" 
               onclick="abrirPlanillaObrero('${cedula}', '${item.id_eval_obreros}')">
              <i class="icon-base bx bx-show me-1"></i>Ver planilla
            </a>
            <a class="dropdown-item" href="javascript:void(0);" 
               onclick="abrirPlanillaObreroEditar('${cedula}', '${item.id_eval_obreros}')">
              <i class="icon-base bx bx-edit-alt me-1"></i>Editar planilla
            </a>
          </div>
        </div>
      `;

      return [
          cedula,
          fullname,
          cargoTexto,
          anioInicio,
          periodoEvaluacion,
          acciones
      ];
  });

  $('#tabla-obreros').DataTable({
      data: tableData,
      columns: [
          { title: "Cédula", width: "120px" },
          { title: "Nombre Completo" },
          { title: "Cargo", width: "200px" },
          { title: "Año", width: "100px" },
          { title: "Período", width: "120px" },
          { title: "Acciones", width: "140px", orderable: false, searchable: false }
      ],
      pageLength: 10,
      responsive: true,
      order: [[0, 'asc']],
      language: {
          search: "Buscar obreros:",
          lengthMenu: "Mostrar _MENU_ registros por página",
          info: "Mostrando _START_ a _END_ de _TOTAL_ obreros",
          infoEmpty: "Mostrando 0 a 0 de 0 obreros",
          emptyTable: "No hay evaluaciones registradas",
          zeroRecords: "No se encontraron coincidencias",
          paginate: { previous: "Anterior", next: "Siguiente" }
      }
  });
}

async function obtenerDatosPersonalesObreros() {
  try {
    const resp = await microApi('controlador/?listar_evaluaciones_obreros');
    return resp;
  } catch (error) {
    console.error('Error al obtener datos de obreros:', error);
    return null;
  }
}
