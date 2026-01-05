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
  
      // Constantes
      const periodoEvaluado   = info.periodo_evaluado || "N/A";
      const fechaInicio       = info.fecha_inicio     || "N/A";
      const fechaCierre       = info.fecha_cierre     || "N/A";
  
      const nombreEvaluado     = info.nombre_evaluado   || "N/A";
      const cedulaEvaluado     = info.cedula_evaluado   || "N/A";
      const cargoEvaluado      = info.cargo_evaluado    || "N/A";
      const ubicacionEval      = info.ubicacion_evaluado|| "N/A";
  
      const nombreEvaluador    = info.nombre_evaluador  || "N/A";
      const cedulaEvaluador    = info.cedula_evaluador  || "N/A";
      const cargoEvaluador     = info.cargo_evaluador   || "N/A";
      const ubicacionEvaluador = info.ubicacion_evaluador|| "N/A";
  
      const nombreSupervisor   = info.nombre_supervisor || "N/A";
      const cedulaSupervisor   = info.cedula_supervisor || "N/A";
      const cargoSupervisor    = info.cargo_supervisor  || "N/A";
  
      const puntajeFinal       = info.puntaje_final     || "N/A";
      const rangoActuacion     = info.rango_actuacion   || "N/A";
  
      const comentarioSup      = info.comentario_supervisor || "";
      const comentarioEval     = info.comentario_evaluado   || "";
      const conformidad        = info.conformidad           || "";
  
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
  
      // Encabezado
      doc.setFontSize(12);
      doc.text("EVALUACION DEL DESEMPEÑO - NIVEL ADMINISTRATIVO", 105, 15, { align: "center" });
  
      let y = 25;
  
      // 🔹 Nueva Sección: Periodo de Evaluación
     // 🔹 Nueva Sección: Periodo de Evaluación
     doc.setFontSize(10);
     doc.text('PERIODO DE EVALUACION', 10, y); 
     y += 5;
     
     doc.autoTable({
       startY: y,
       head: [['Campo', 'Valor']],   // encabezado de la tabla
       body: [
         ['Fecha Inicio', fechaInicio],
         ['Fecha Cierre', fechaCierre],
         ['Periodo', periodoEvaluado]
       ],
       styles: { fontSize: 8, cellPadding: 0.5 },
       margin: { left: 10 },   // 🔹 más pegado al margen izquierdo
       columnStyles: {
         0: { cellWidth: 30 }, // columna "Campo" más estrecha
         1: { cellWidth: 30 }  // columna "Valor" más estrecha
       }
     });
     
     // actualizar y para continuar con la siguiente sección
     y = doc.lastAutoTable.finalY + 8;
      // Sección A: Datos de identificación
      doc.setFontSize(10);
      doc.text('SECCION "A": DATOS DE IDENTIFICACION', 10, y); y += 5;
      doc.setFontSize(8);
  
      // Evaluado
doc.setFont("helvetica", "bold");
doc.text("Evaluado:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(`${nombreEvaluado}`, 26, y);
y += 4;

// Cédula Evaluado
doc.setFont("helvetica", "bold");
doc.text("Cedula:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(`${cedulaEvaluado}`, 21, y);
y += 4;

// Cargo
doc.setFont("helvetica", "bold");
doc.text("Cargo:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(cargoEvaluado, 20, y);
y += 4;

// Ubicación Administrativa
doc.setFont("helvetica", "bold");
doc.text("Ubicación Administrativa:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(ubicacionEval, 46, y);
y += 6;

// Evaluador
doc.setFont("helvetica", "bold");
doc.text("Evaluador:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(`${nombreEvaluador}`, 26, y);
y += 4;

// Cédula Evaluador
doc.setFont("helvetica", "bold");
doc.text("Cedula:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(`${cedulaEvaluador}`, 21, y);
y += 4;

// Cargo Evaluador
doc.setFont("helvetica", "bold");
doc.text("Cargo:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(cargoEvaluador, 20, y);
y += 4;

// Ubicación Evaluador
doc.setFont("helvetica", "bold");
doc.text("Ubicación Administrativa:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(ubicacionEvaluador, 46, y);
y += 6;

// Supervisor
doc.setFont("helvetica", "bold");
doc.text("Supervisor del Evaluador:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(`${nombreSupervisor}`, 47, y);
y += 4;

// Cédula Supervisor
doc.setFont("helvetica", "bold");
doc.text("Cedula:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(`${cedulaSupervisor}`, 21, y);
y += 4;

// Cargo Supervisor
doc.setFont("helvetica", "bold");
doc.text("Cargo:", 10, y);
doc.setFont("helvetica", "normal");
doc.text(cargoSupervisor, 20, y);
y += 8;
  
  
      ``
      // Sección B: Objetivos
      doc.setFontSize(10);
      doc.text('SECCION "B": OBJETIVOS', 10, y); y += 4;
      doc.autoTable({
        startY: y,
        head: [['Objetivo', 'Peso', 'Rango', 'PesoxRango']],
        body: objetivos.map(obj => [
          obj.nombre_objetivo,
          obj.peso_objetivo,
          obj.rango_obj,
          obj.pesoxrango_obj
        ]),
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 },
        columnStyles: {
          0: { cellWidth: 40 }, // columna "Campo" más estrecha
          1: { cellWidth: 40 }  // columna "Valor" más estrecha
        }
        
      });
      y = doc.lastAutoTable.finalY + 6;
  
      // Sección C: Competencias
      doc.setFontSize(10);
      doc.text('SECCION "C": COMPETENCIAS', 10, y); y += 4;
      doc.autoTable({
        startY: y,
        head: [['Competencia', 'Peso', 'Rango', 'PesoxRango']],
        body: competencias.map(comp => [
          comp.nombre_competencia,
          comp.peso_competencia,
          comp.rango_comp,
          comp.pesoxrango_comp
        ]),
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 },
        columnStyles: {
          0: { cellWidth: 40 }, // columna "Campo" más estrecha
          1: { cellWidth: 40 }  // columna "Valor" más estrecha
        }
      });
      y = doc.lastAutoTable.finalY + 6;
  
      // Sección D: Resultado final
      doc.setFontSize(10);
      doc.text('SECCION "D": RESULTADO FINAL', 10, y); y += 4;
      doc.autoTable({
        startY: y,
        head: [['Puntaje Final', 'Rango de Actuación']],
        body: [[puntajeFinal, rangoActuacion]],
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 },
        columnStyles: {
          0: { cellWidth: 40 }, // columna "Campo" más estrecha
          1: { cellWidth: 40 }  // columna "Valor" más estrecha
        }
      });
      y = doc.lastAutoTable.finalY + 6;
  
      // Sección E: Comentarios y conformidad
      doc.setFontSize(10);
      doc.text('SECCION "E": COMENTARIOS Y CONFORMIDAD', 10, y); y += 4;
      doc.autoTable({
        startY: y,
        head: [['Comentario Del Supervisor', 'Comentario Del Evaluado', 'Esta usted de acuerdo con su evaluacion?']],
        body: [[comentarioSup, comentarioEval, conformidad]],
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 },
        columnStyles: {
          0: { cellWidth: 40 }, // columna "Campo" más estrecha
          1: { cellWidth: 40 }  // columna "Valor" más estrecha
        }
      });

      // Sección F: Firmas
// Sección F: Firmas en una sola línea
doc.setFontSize(8); // 🔹 fuente pequeña para que quepan
let yFirmas = doc.lastAutoTable ? doc.lastAutoTable.finalY + 10 : y + 10;

// Firma Evaluador
doc.text("Firma Evaluador", 30, yFirmas);
doc.line(20, yFirmas + 8, 60, yFirmas + 8); // línea debajo

// Firma Supervisor del Evaluador
doc.text("Firma Supervisor del Evaluador", 95, yFirmas);
doc.line(85, yFirmas + 8, 145, yFirmas + 8);

// Firma de Evaluado
doc.text("Firma de Evaluado", 160, yFirmas);
doc.line(150, yFirmas + 8, 200, yFirmas + 8);
      // Descargar
      doc.save(`Reporte_Evaluacion_${cedulaEvaluado}.pdf`);
    } catch (error) {
      console.error("Error al generar PDF:", error);
    }
  }