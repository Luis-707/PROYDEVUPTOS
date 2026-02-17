// =============================
// Listar evaluaciones obreras para comentar
// =============================
async function listarEvaluadosComentariosObreros() {

    const resp = await obtenerDatosPersonalesComentariosObreros();

    if (!resp || resp.success !== true) {
        console.error("Error en respuesta:", resp);
        return;
    }

    const registros = Array.isArray(resp.data) ? resp.data : [];

    if ($.fn.DataTable.isDataTable('#tabla-evaluadosComentariosObreros')) {
        $('#tabla-evaluadosComentariosObreros').DataTable().destroy();
    }
    $('#tabla-evaluadosComentariosObreros tbody').empty();

    const tableData = registros.map(item => {
        const cedula  = item.cedula_usuario || "N/D";
        const nombre  = item.nombre_completo || "N/D";
        const cargo   = item.cargo || "N/D";
        const anio    = item.anio_inicio || "N/D";
        const periodo = item.periodo_evaluacion || "N/D";

        const acciones = `
            <button type="button" class="btn btn-secondary btn-sm"
                onclick="abrirPlanillaObreroReadonly('${cedula}', '${item.id_eval_obreros}')">
                Ver evaluación
            </button>
        `;

        return [cedula, nombre, cargo, anio, periodo, acciones];
    });

    $('#tabla-evaluadosComentariosObreros').DataTable({
        data: tableData,
        columns: [
            { title: "Cédula" },
            { title: "Nombre Completo" },
            { title: "Cargo" },
            { title: "Año" },
            { title: "Período" },
            { title: "Acciones", orderable: false, searchable: false }
        ],
        pageLength: 10,
        responsive: true,
        order: [[0, 'asc']],
        language: {
            search: "Buscar:",
            emptyTable: "No hay evaluaciones obreras finalizadas",
            zeroRecords: "No se encontraron coincidencias"
        }
    });
}

// =============================
// Servicio backend
// =============================
async function obtenerDatosPersonalesComentariosObreros() {
    try {
        return await microApi('controlador/?listar_comentarios_obreros');
    } catch (e) {
        console.error("Error:", e);
        return null;
    }
}