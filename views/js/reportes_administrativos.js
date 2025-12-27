// Listar evaluaciones disponibles para reporte
async function listarReportesAdministrativos() {
  try {
    const resp = await microApi('controlador/?listarReportesAdmin'); // servicio que devuelve evaluaciones
    
    if (!resp || !resp.success) {
      console.error("Error en respuesta:", resp);
      return;
    }

    const data = Array.isArray(resp.data[0]) ? resp.data[0] : resp.data; 
    
    console.log("Data normalizada:", data);

   
    if (!Array.isArray(data)) {
      console.error("Respuesta inválida:", data);
      return;
    }

    const tbody = document.querySelector("#tabla-reportes tbody");
    tbody.innerHTML = "";

    data.forEach(item => {
      const cedula = item.cedula_usuario;
      const fullname = item.nombre_completo || "No encontrado";
      const cargo = item.cargo_evaluado || "Sin cargo";
      const anio = item.anio_inicio || "N/A";
      const periodo = item.periodo_evaluado || "N/A";
      const conformidad = item.conformidad || "";

      const puedeDescargar = item.comentario_supervisor && item.comentario_evaluado && item.conformidad;

      let acciones = "";
      if (puedeDescargar) {
        acciones = `
          <button type="button" class="btn btn-danger btn-sm" onclick="generarPDF(${item.id_eval_admin})">
            Descargar PDF
          </button>
        `;
      } else {
        acciones = `<span class="text-muted">Pendiente</span>`;
      }

      const fila = `
        <tr>
          <td>${cedula}</td>
          <td>${fullname}</td>
          <td>${cargo}</td>
          <td>${anio}</td>
          <td>${periodo}</td>
          <td>${conformidad || "N/A"}</td>
          <td>${acciones}</td>
        </tr>
      `;
      tbody.innerHTML += fila;
    });

    console.log("Respuesta cruda:", resp);
    console.log("Data:", resp.data);
  } catch (error) {
    console.error("Error al listar reportes:", error);
  }
}
  
  // Generar PDF con jsPDF
  async function generarPDF(idEvalAdmin) {
    try {
      const resp = await microApi(`controlador/?datos_reportes&id_eval_admin=${idEvalAdmin}`);
      if (!resp || !resp.success) {
        console.error("Error en respuesta:", resp);
        return;
      }
  
      const info        = resp.data || {};
      const objetivos   = Array.isArray(resp.objetivos) ? resp.objetivos : [];
      const competencias= Array.isArray(resp.competencias) ? resp.competencias : [];
  
      // Debug esencial
      console.log("Info PDF:", info);
  
      // Guardas para imprimir sin undefined
      const nombreEvaluado   = info.nombre_evaluado   || "N/A";
      const cedulaEvaluado   = info.cedula_evaluado   || "N/A";
      const cargoEvaluado    = info.cargo_evaluado    || "N/A";
      const nombreEvaluador  = info.nombre_evaluador  || "N/A";
      const cedulaEvaluador  = info.cedula_evaluador  || "N/A";
      const nombreSupervisor = info.nombre_supervisor || "N/A";
      const cedulaSupervisor = info.cedula_supervisor || "N/A";
      const puntajeFinal     = info.puntaje_final     || "N/A";
      const rangoActuacion   = info.rango_actuacion   || "N/A";
      const comentarioSup    = info.comentario_supervisor || "";
      const comentarioEval   = info.comentario_evaluado   || "";
      const conformidad      = info.conformidad           || "";
  
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
  
      doc.setFontSize(14);
      doc.text("EVALUACION DEL DESEMPEÑO - NIVEL ADMINISTRATIVO", 105, 15, { align: "center" });
  
      doc.setFontSize(12);
      doc.text('SECCION "A": DATOS DE IDENTIFICACION', 10, 30);
      doc.setFontSize(10);
      doc.text(`Evaluado: ${nombreEvaluado} (${cedulaEvaluado})`, 10, 40);
      doc.text(`Cargo: ${cargoEvaluado}`, 10, 46);
      doc.text(`Evaluador: ${nombreEvaluador} (${cedulaEvaluador})`, 10, 52);
      doc.text(`Supervisor: ${nombreSupervisor} (${cedulaSupervisor})`, 10, 58);
  
      doc.setFontSize(12);
      doc.text('SECCION "B": OBJETIVOS', 10, 70);
      doc.setFontSize(10);
      let y = 78;
      objetivos.forEach(obj => {
        doc.text(`${obj.nombre_objetivo} | Peso: ${obj.peso_objetivo} | Rango: ${obj.rango_obj} | PxR: ${obj.pesoxrango_obj}`, 10, y);
        y += 6;
      });
  
      doc.setFontSize(12);
      doc.text('SECCION "C": COMPETENCIAS', 10, y + 10);
      y += 18;
      doc.setFontSize(10);
      competencias.forEach(comp => {
        doc.text(`${comp.nombre_competencia} | Peso: ${comp.peso_competencia} | Rango: ${comp.rango_comp} | PxR: ${comp.pesoxrango_comp}`, 10, y);
        y += 6;
      });
  
      doc.setFontSize(12);
      doc.text('SECCION "D": RESULTADO FINAL', 10, y + 10);
      y += 18;
      doc.setFontSize(10);
      doc.text(`Puntaje Final: ${puntajeFinal}`, 10, y);
      y += 6;
      doc.text(`Rango de Actuacion: ${rangoActuacion}`, 10, y);
      y += 10;
  
      doc.text(`Comentario Supervisor: ${comentarioSup}`, 10, y);
      y += 6;
      doc.text(`Comentario Evaluado: ${comentarioEval}`, 10, y);
      y += 6;
      doc.text(`Conformidad: ${conformidad}`, 10, y);
  
      doc.save(`Reporte_Evaluacion_${cedulaEvaluado}.pdf`);
    } catch (error) {
      console.error("Error al generar PDF:", error);
    }
  }