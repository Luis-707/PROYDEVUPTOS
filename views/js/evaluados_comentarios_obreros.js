// =============================
// Listar evaluaciones obreras para comentar
// =============================
async function listarEvaluadosComentariosObreros() {

    // 1) Obtener datos desde el backend
    const datos = await obtenerDatosPersonalesComentariosObreros();
    if (!datos) return;

    // 2) Reiniciar DataTable si existe
    if ($.fn.DataTable.isDataTable('#tabla-evaluadosComentariosObreros')) {
        $('#tabla-evaluadosComentariosObreros').DataTable().destroy();
    }

    $('#tabla-evaluadosComentariosObreros tbody').empty();

    // 3) Normalizar registros
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

    const tableData = registros.map(item => {
        const cedula = String(item.cedula_usuario).trim();
        const fullname = item.nombre_completo || "No encontrado";
        const cargo = item.cargo_evaluado || "Sin cargo";
        const anio = item.anio_inicio || "N/D";
        const periodo = item.periodo_evaluacion || "N/D";

        const acciones = `
            <button type="button" class="btn btn-secondary btn-sm"
                onclick="abrirPlanillaObreroReadonly('${cedula}', '${item.id_eval_obreros}')">
                Ver evaluación
            </button>
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
    $('#tabla-evaluadosComentariosObreros').DataTable({
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
// Llamar al servicio backend
// =============================
async function obtenerDatosPersonalesComentariosObreros() {
    try {
        let resp = await microApi('controlador/?listar_comentarios_obreros');
        return resp;
    } catch (error) {
        console.error('Error al obtener datos obreros:', error);
        return null;
    }
}