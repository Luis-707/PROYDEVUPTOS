// Listar evaluados en la tabla
async function listarEvaluados() {
  // 1) Obtener datos de SQL
  const datosPersonales = await obtenerDatosPersonales();
  if (!datosPersonales) return;

  // 2) Preparar tabla
  const tbody = document.querySelector("#tabla-evaluados tbody");
  tbody.innerHTML = "";

  const registros = Array.isArray(datosPersonales[0]) ? datosPersonales.flat() : datosPersonales;

  let html = "";
  registros.forEach(item => {
    const cedula = String(item.cedula_usuario).trim();
    const fullname = item.nombre_completo || "No encontrado";
    const cargoTexto = item.cargo_evaluado || "Sin cargo";
    const periodoEvaluado = item.periodo_evaluado || "N/A";
    const anioInicio = item.anio_inicio;

    html += `
      <tr>
        <td>${cedula}</td>
        <td>${fullname}</td>
        <td>${cargoTexto}</td>
        <td>${anioInicio}</td>
        <td>${periodoEvaluado}</td>
        <td>
          <button type="button" class="btn btn-info btn-sm" onclick="abrirPlanilla('${cedula}', '${item.id_eval_admin}')">
            Ver planilla
          </button>
          <button type="button" class="btn btn-warning btn-sm" onclick="abrirPlanillaEditar('${cedula}', '${item.id_eval_admin}')">
            Editar planilla
          </button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
}

// Función para llamar al servicio SQL listar_datos_personales
async function obtenerDatosPersonales() {
  try {
    let resp = await microApi('controlador/?listar_evaluaciones');
    return resp;
  } catch (error) {
    console.error('Error al obtener datos personales:', error);
    return null;
  }
}
