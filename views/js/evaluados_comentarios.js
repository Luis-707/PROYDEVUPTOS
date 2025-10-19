// Listar evaluados en la tabla
async function listarEvaluadosComentarios() {
    // 1) Obtener datos de SQL (incluye periodo_evaluado)
    const datosPersonales = await obtenerDatosPersonales();
    if (!datosPersonales) return;
  
    // 2) Obtener JSON de empleados
    const empleados = await relacionarDatosEmpleados();
    if (!empleados.length) return;
  
    // 3) Preparar tabla
    const tbody = document.querySelector("#tabla-evaluadosComentarios tbody");
    tbody.innerHTML = "";
  
    const registros = Array.isArray(datosPersonales[0]) ? datosPersonales.flat() : datosPersonales;
  
    let html = "";
    registros.forEach(item => {
      const cedula = String(item.cedula_usuario).trim();
      const empleado = empleados.find(emp => emp.pin_str === cedula || emp.pin === cedula);
  
      const fullname = empleado ? empleado.fullname : "No encontrado";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const unidadAdmin = empleado ? empleado.additional : "N/D";
      const periodo = item.periodo_evaluado || "N/D";
  
      html += `
        <tr>
          <td>${cedula}</td>
          <td>${fullname}</td>
          <td>${cargoTexto}</td>
          <td>${unidadAdmin}</td>
          <td>${periodo}</td>
          <td>
            
            <button type="button" class="btn btn-secondary btn-sm" onclick="abrirPlanillaReadonly('${cedula}')">
              Ver evaluación
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
      let resp = await microApi('controlador/?lista_comentarios');
      return resp;
    } catch (error) {
      console.error('Error al obtener datos personales:', error);
      return null;
    }
  }
  
  // Función para cargar JSON empleados y retornar array con fullname y additional
  async function relacionarDatosEmpleados() {
    try {
      const resp = await microApi('views/js/datos_empleado.json');
      if (Array.isArray(resp)) {
        return resp[0]?.data || resp[0] || [];
      } else if (resp?.data) {
        return resp.data;
      }
      return [];
    } catch (error) {
      console.error('Error al cargar datos empleados:', error);
      return [];
    }
  }