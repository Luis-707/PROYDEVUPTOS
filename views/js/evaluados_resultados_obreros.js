// =============================
// Listar resultados OBREROS
// =============================
async function listarEvaluadosResultadosObreros() {

    // 1) Obtener datos desde el backend
    const resp = await obtenerDatosResultadosObreros();
    if (!resp || resp.success !== true) {
        console.error("Error en respuesta:", resp);
        return;
    }

    let registros = [];

    if (Array.isArray(resp.data[0])) {
        registros = resp.data[0];
    } else if (Array.isArray(resp.data)) {
        registros = resp.data;
    } else {
        registros = [];
    }

    // 2) Reiniciar DataTable si existe
    if ($.fn.DataTable.isDataTable('#tabla-resultadosObreros')) {
        $('#tabla-resultadosObreros').DataTable().destroy();
    }

    // 3) Limpiar tabla
    $('#tabla-resultadosObreros tbody').empty();

    // 4) Preparar datos para DataTables
    const tableData = registros.map(item => {
        const cedula   = item.cedula_usuario || "N/D";
        const fullname = item.nombre_completo || "No encontrado";
        const cargo    = item.cargo_obrero || "Sin cargo";
        const ubicacion = item.ubicacion || "N/D";
        const anio     = item.anio_inicio || "N/D";

        const acciones = `
            <button type="button" class="btn btn-secondary btn-sm"
                onclick="abrirPlanillaResultadosObrero('${cedula}', '${item.id_eval_obreros}')">
                Ver evaluación
            </button>
        `;

        return [
            cedula,
            fullname,
            cargo,
            ubicacion,
            anio,
            acciones
        ];
    });

    // 5) Inicializar DataTable
    $('#tabla-resultadosObreros').DataTable({
        data: tableData,
        columns: [
            { title: "Cédula", width: "120px" },
            { title: "Nombre Completo" },
            { title: "Cargo", width: "200px" },
            { title: "Ubicación", width: "180px" },
            { title: "Año", width: "100px" },
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
            emptyTable: "No hay evaluaciones obreras finalizadas",
            zeroRecords: "No se encontraron evaluados coincidentes",
            paginate: {
                previous: "Anterior",
                next: "Siguiente"
            }
        }
    });
}

// =============================
// Servicio backend
// =============================
async function obtenerDatosResultadosObreros() {
    try {
        return await microApi('controlador/?lista_resultados_obreros');
    } catch (error) {
        console.error("Error al obtener datos obreros:", error);
        return null;
    }
}