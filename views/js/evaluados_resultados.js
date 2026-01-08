  // Listar evaluados en la tabla
  async function listarEvaluadosResultados() {
    // 1) Obtener datos de SQL
    const datosPersonales = await obtenerDatosPersonalesResultados();
    if (!datosPersonales) return;
  
    // 2) Destruir DataTable existente si existe
    if ($.fn.DataTable.isDataTable('#tabla-evaluadosResultados')) {
        $('#tabla-evaluadosResultados').DataTable().destroy();
    }
    
    // 3) Limpiar tbody
    $('#tabla-evaluadosResultados tbody').empty();
    
    // 4) Preparar datos para DataTables
    const registros = Array.isArray(datosPersonales[0]) ? datosPersonales.flat() : datosPersonales;
    
    const tableData = registros.map(item => {
        const cedula = String(item.cedula_usuario).trim();
        const fullname = item.nombre_completo || "No encontrado";
        const cargoTexto = item.cargo_evaluado || "Sin cargo";
        const unidadAdmin = item.ubicacion_administrativa || "N/D";
        const periodo = item.periodo_evaluado || "N/D";
        const anioInicio = item.anio_inicio || "N/D";
        
        // Botón EXACTAMENTE igual al original
        const acciones = `
            <button type="button" class="btn btn-secondary btn-sm" 
                    onclick="abrirPlanillaResultados('${cedula}','${item.id_eval_admin}')">
              Ver evaluación
            </button>
        `;
        
        return [
            cedula,
            fullname,
            cargoTexto,
            unidadAdmin,
            anioInicio,
            periodo,
            acciones
        ];
    });
    
    // 5) Inicializar DataTable
    $('#tabla-evaluadosResultados').DataTable({
        data: tableData,
        columns: [
            { title: "Cédula", width: "120px" },
            { title: "Nombre Completo" },
            { title: "Cargo Evaluado", width: "200px" },
            { title: "Ubicacion", width: "180px" },
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
            emptyTable: "No hay resultados de evaluaciones",
            zeroRecords: "No se encontraron evaluados coincidentes",
            paginate: {
                previous: "Anterior",
                next: "Siguiente"
            }
        }
    });
  }
  
  
  // Función para llamar al servicio SQL lista_comentarios
  async function obtenerDatosPersonalesResultados() {
    try {
      let resp = await microApi('controlador/?lista_resultados');
      return resp;
    } catch (error) {
      console.error('Error al obtener datos personales:', error);
      return null;
    }
  }