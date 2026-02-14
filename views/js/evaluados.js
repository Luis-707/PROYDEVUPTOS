// ===============================
// LISTAR EVALUACIONES ADMINISTRATIVAS
// ===============================
async function listarEvaluados() {

    // 1) Obtener datos del backend
    const resp = await microApi('controlador/?listar_evaluaciones');
  
    if (!resp || !resp.success || !Array.isArray(resp.data)) {
      console.error("Error al cargar evaluaciones:", resp);
      return;
    }
  
    const registros = resp.data;
  
    // 2) Destruir DataTable si existe
    if ($.fn.DataTable.isDataTable('#tabla-evaluados')) {
      $('#tabla-evaluados').DataTable().destroy();
    }
  
    $('#tabla-evaluados tbody').empty();
  
    // 3) Preparar filas
    const tableData = registros.map(item => {
  
      const cedula = item.cedula || "N/D";
      const fullname = item.nombre_completo || "N/D";
      const cargo = item.cargo || "Sin cargo";
      const anio = item.anio_inicio || "N/A";
      const periodo = item.periodo_evaluado || "N/A";
  
      const acciones = `
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0);" 
               onclick="abrirPlanilla('${cedula}','${item.evaluado_id}', '${item.id_eval_admin}')">
                <i class="icon-base bx bx-show me-1"></i>Ver planilla
            </a>
            <a class="dropdown-item" href="javascript:void(0);" 
               onclick="abrirPlanillaEditar('${item.evaluado_id}', '${item.id_eval_admin}')">
                <i class="icon-base bx bx-edit-alt me-1"></i>Editar planilla
            </a>
          </div>
        </div>
      `;
  
      return [
        cedula,
        fullname,
        cargo,
        anio,
        periodo,
        acciones
      ];
    });
  
    // 4) Inicializar DataTable
    $('#tabla-evaluados').DataTable({
      data: tableData,
      columns: [
        { title: "Cédula", width: "120px" },
        { title: "Nombre Completo" },
        { title: "Cargo", width: "200px" },
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
      order: [[0, 'asc']],
      language: {
        search: "Buscar evaluados:",
        lengthMenu: "Mostrar _MENU_ registros por página",
        info: "Mostrando _START_ a _END_ de _TOTAL_ evaluados",
        infoEmpty: "Mostrando 0 a 0 de 0 evaluados",
        emptyTable: "No hay evaluaciones registradas",
        zeroRecords: "No se encontraron evaluaciones coincidentes",
        paginate: {
          previous: "Anterior",
          next: "Siguiente"
        }
      }
    });
  }
  