  // Listar evaluados en la tabla
  async function listarEvaluadosResultados() {
    // 1) Obtener datos de SQL (incluye periodo_evaluado y anio_inicio)
    const datosPersonales = await obtenerDatosPersonalesResultados();
    if (!datosPersonales) return;
  
    // 2) Preparar tabla
    const tbody = document.querySelector("#tabla-evaluadosResultados tbody");
    tbody.innerHTML = "";
  
    const registros = Array.isArray(datosPersonales[0]) ? datosPersonales.flat() : datosPersonales;
  
    let html = "";
    registros.forEach(item => {
      const cedula = String(item.cedula_usuario).trim();
      const fullname = item.nombre_completo || "No encontrado";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const unidadAdmin = item.ubicacion_administrativa || "N/D";
      const periodo = item.periodo_evaluado || "N/D";
      const anioInicio = item.anio_inicio;
  
      html += `
        <tr>
          <td>${cedula}</td>
          <td>${fullname}</td>
          <td>${cargoTexto}</td>
          <td>${unidadAdmin}</td>
          <td>${anioInicio}</td>
          <td>${periodo}</td>
          <td>
            <button type="button" class="btn btn-secondary btn-sm" 
                    onclick="abrirPlanillaResultados('${cedula}','${item.id_eval_admin}')">
              Ver evaluación
            </button>
          </td>
        </tr>
      `;
    });
  
    tbody.innerHTML = html;
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