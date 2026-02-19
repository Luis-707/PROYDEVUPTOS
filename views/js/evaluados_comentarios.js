// =============================
// Listar evaluaciones ADMINISTRATIVAS para comentar
// =============================
async function listarEvaluadosComentarios() {

  // 1) Obtener datos desde el backend
  const resp = await obtenerDatosPersonalesComentarios();
  if (!resp || resp.success !== true) {
      console.error("Error en respuesta:", resp);
      return;
  }

  let registros = [];

if (Array.isArray(resp.data[0])) {
    // Caso: data = [ [ {..}, {..} ] ]
    registros = resp.data[0];
} else if (Array.isArray(resp.data)) {
    // Caso: data = [ {..}, {..} ]
    registros = resp.data;
} else {
    registros = [];
}

  // 2) Reiniciar DataTable si existe
  if ($.fn.DataTable.isDataTable('#tabla-evaluadosComentarios')) {
      $('#tabla-evaluadosComentarios').DataTable().destroy();
  }

  // 3) Limpiar tabla
  $('#tabla-evaluadosComentarios tbody').empty();

  // 4) Preparar datos para DataTables (MISMA LÓGICA QUE OBREROS)
  const tableData = registros.map(item => {
      const cedula   = item.cedula_usuario || "N/D";
      const fullname = item.nombre_completo || "No encontrado";
      const cargo    = item.cargo || "Sin cargo";
      const unidad   = item.unidad || "N/D"; // Ubicación administrativa
      const anio     = item.anio_inicio || "N/D";
      const periodo  = item.periodo_evaluado || "N/D";

      // Botón EXACTAMENTE igual al de obreros, pero versión administrativa
      const acciones = `
          <button type="button" class="btn btn-secondary btn-sm"
              onclick="abrirPlanillaReadonly('${cedula}', '${item.id_eval_admin}')">
              Ver evaluación
          </button>
      `;

      return [
          cedula,
          fullname,
          cargo,
          unidad,
          anio,
          periodo,
          acciones
      ];
  });

  // 5) Inicializar DataTable (MISMA CONFIGURACIÓN QUE OBREROS)
  $('#tabla-evaluadosComentarios').DataTable({
      data: tableData,
      columns: [
          { title: "Cédula", width: "120px" },
          { title: "Nombre Completo" },
          { title: "Cargo Evaluado", width: "200px" },
          { title: "Ubicación", width: "180px" },
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
          emptyTable: "No hay evaluaciones administrativas finalizadas",
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
async function obtenerDatosPersonalesComentarios() {
  try {
      return await microApi('controlador/?lista_comentarios');
  } catch (error) {
      console.error("Error al obtener datos administrativos:", error);
      return null;
  }

}