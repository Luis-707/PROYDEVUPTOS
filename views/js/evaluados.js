// Listar evaluados en tabla DataTables
async function listarEvaluados() {
  // 1) Obtener datos de SQL
  const datosPersonales = await obtenerDatosPersonales();
  if (!datosPersonales) return;

  // 2) Destruir DataTable existente si existe
  if ($.fn.DataTable.isDataTable('#tabla-evaluados')) {
      $('#tabla-evaluados').DataTable().destroy();
  }
  
  // 3) Limpiar tbody
  $('#tabla-evaluados tbody').empty();
  
  // 4) Preparar datos para DataTables
  const registros = Array.isArray(datosPersonales[0]) ? datosPersonales.flat() : datosPersonales;
  
  const tableData = registros.map(item => {
      const cedula = String(item.cedula_usuario).trim();
      const fullname = item.nombre_completo || "No encontrado";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const periodoEvaluado = item.periodo_evaluado || "N/A";
      const anioInicio = item.anio_inicio || "N/A";
      
      // Botones de acción con dropdown como tu ejemplo
      const acciones = `
          <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" 
                     onclick="abrirPlanilla('${cedula}', '${item.id_eval_admin}')">
                      <i class="icon-base bx bx-show me-1"></i>Ver planilla
                  </a>
                  <a class="dropdown-item" href="javascript:void(0);" 
                     onclick="abrirPlanillaEditar('${cedula}', '${item.id_eval_admin}')">
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
          periodoEvaluado,
          acciones
      ];
  });
  
  // 5) Inicializar DataTable
  $('#tabla-evaluados').DataTable({
      data: tableData,
      columns: [
          { title: "Cédula", width: "120px" },
          { title: "Nombre Completo" },
          { title: "Cargo Evaluado", width: "200px" },
          { title: "Año", width: "100px" },
          { title: "Período", width: "120px" },
          { 
              title: "Acciones", 
              width: "140px",
              orderable: false,
              searchable: false
          }
      ],
      pageLength: 10,
      responsive: true,
      order: [[0, 'asc']], // Ordenar por cédula por defecto
      language: {
          search: "Buscar evaluados:",
          lengthMenu: "Mostrar _MENU_ registros por página",
          info: "Mostrando _START_ a _END_ de _TOTAL_ evaluados",
          infoEmpty: "Mostrando 0 a 0 de 0 evaluados",
          emptyTable: "No hay evaluados registrados",
          zeroRecords: "No se encontraron evaluados coincidentes",
          paginate: {
              previous: "Anterior",
              next: "Siguiente"
          }
      }
  });
}

// Función para llamar al servicio SQL (sin cambios)
async function obtenerDatosPersonales() {
  try {
    let resp = await microApi('controlador/?listar_evaluaciones');
    return resp;
  } catch (error) {
    console.error('Error al obtener datos personales:', error);
    return null;
  }
}
