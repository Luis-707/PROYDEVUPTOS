// =============================
// Listar evaluaciones ADMINISTRATIVAS para comentar
// =============================
async function listarEvaluadosResultados() {

  // 1) Obtener datos desde el backend
  const resp = await obtenerDatosPersonalesResultados();
  if (!resp || resp.success !== true) {
      console.error("Error en respuesta:", resp);
      return;
  }

  console.log("Datos administrativos recibidos:", resp.data);

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

// =============================
  // Llenar filtro de períodos
  // =============================
  const select = document.getElementById("filtroPeriodo");
  const periodosUnicos = [...new Set(registros.map(r => r.periodo_evaluado))];

  select.innerHTML = `<option value="todos">Todos los períodos</option>`;
  periodosUnicos.forEach(p => {
      select.innerHTML += `<option value="${p}">${p}</option>`;
  });

  // =============================
  // Construir gráfica al cargar
  // =============================
  construirGraficaRangos(registros, "todos");

  // =============================
  // Actualizar gráfica al cambiar período
  // =============================
  select.addEventListener("change", () => {
      construirGraficaRangos(registros, select.value);
  });


  // 2) Reiniciar DataTable si existe
  if ($.fn.DataTable.isDataTable('#tabla-evaluadosResultados')) {
      $('#tabla-evaluadosResultados').DataTable().destroy();
  }

  // 3) Limpiar tabla
  $('#tabla-evaluadosResultados tbody').empty();

  // 4) Preparar datos para DataTables (MISMA LÓGICA QUE OBREROS)
  const tableData = registros.map(item => {
      const cedula   = item.cedula_usuario || "N/D";
      const fullname = item.nombre_completo || "No encontrado";
      const cargo    = item.cargo_evaluado || "Sin cargo";
      const unidad   = item.ubicacion_administrativa || "N/D"; // Ubicación administrativa
      const anio     = item.anio_inicio || "N/D";
      const periodo  = item.periodo_evaluado || "N/D";

      // Botón EXACTAMENTE igual al de obreros, pero versión administrativa
      const acciones = `
          <button type="button" class="btn btn-secondary btn-sm"
              onclick="abrirPlanillaResultados('${cedula}', '${item.id_eval_admin}')">
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
  $('#tabla-evaluadosResultados').DataTable({
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


let graficaRangos = null;

// =============================
// Construir gráfica
// =============================
function construirGraficaRangos(registros, periodoSeleccionado) {

    // Filtrar por período
    let filtrados = registros;
    if (periodoSeleccionado !== "todos") {
        filtrados = registros.filter(r => r.periodo_evaluado === periodoSeleccionado);
    }

    // Contar por rango
    const conteo = {};
    filtrados.forEach(r => {
        const rango = r.rango_actuacion || "Sin rango";
        conteo[rango] = (conteo[rango] || 0) + 1;
    });

    const labels = Object.keys(conteo);
    const valores = Object.values(conteo);

    // Destruir gráfica previa
    if (graficaRangos) graficaRangos.destroy();

    const ctx = document.getElementById("graficaRangos");

    graficaRangos = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Cantidad de evaluados",
                data: valores,
                backgroundColor: "#4e73df"
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}


// =============================
// Servicio backend
// =============================
async function obtenerDatosPersonalesResultados() {
  try {
      return await microApi('controlador/?lista_resultados');
  } catch (error) {
      console.error("Error al obtener datos administrativos:", error);
      return null;
  }

}