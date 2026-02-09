async function listarReportesAdministrativos() {
  try {
    const resp = await microApi('controlador/?listarReportesAdmin');
    if (!resp || !resp.success) return;

    const data = Array.isArray(resp.data[0]) ? resp.data[0] : resp.data;
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
          <button type="button" class="btn btn-info btn-sm" onclick="abrirPlanillaExcepcional('${cedula}', ${item.id_eval_admin})">
            Planilla Excepcional
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
  } catch (error) {
    console.error("Error al listar reportes:", error);
  }
}

 // Generar PDF con jsPDF
async function generarPDF(idEvalAdmin) {
  try {
    // 1) Obtener datos de la evaluación administrativa
    const resp = await microApi(`controlador/?datos_reportes&id_eval_admin=${idEvalAdmin}`);
    if (!resp || !resp.success) {
      console.error("Error en respuesta:", resp);
      return;
    }

    const info         = resp.data || {};
    const objetivos    = Array.isArray(resp.objetivos) ? resp.objetivos : [];
    const competencias = Array.isArray(resp.competencias) ? resp.competencias : [];

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // 🔹 Primera hoja: Planilla Administrativa
    generarPlanillaAdministrativa(doc, info, objetivos, competencias);

    // 2) Verificar si existe planilla de desempeño excepcional
    const respExcep = await microApi(`controlador/?datos_combinados&id_eval_admin=${idEvalAdmin}`);
    if (respExcep && respExcep.success && respExcep.data) {
      const excepInfo = respExcep.data;
      // Normalizar motivos: siempre array
      const motivos = Array.isArray(respExcep.motivos) ? respExcep.motivos : [];

      if (motivos.length > 0) {
        // 🔹 Segunda hoja: Planilla de Desempeño Excepcional
        doc.addPage();
        generarPlanillaExcepcional(doc, excepInfo, motivos);
      } else {
        console.warn("No hay motivos registrados para esta planilla excepcional.");
      }
    }

    // Descargar
    doc.save(`Reporte_Evaluacion_${info.cedula_evaluado}.pdf`);
  } catch (error) {
    console.error("Error al generar PDF:", error);
  }
}
  
  
  
  
  function generarPlanillaAdministrativa(doc, info, objetivos, competencias) {
    // Definir constantes al inicio
    const periodoEvaluado   = info.periodo_evaluado   || "N/A";
    const fechaInicio       = info.fecha_inicio       || "N/A";
    const fechaCierre       = info.fecha_cierre       || "N/A";
  
    const nombreEvaluado    = info.nombre_evaluado    || "N/A";
    const cedulaEvaluado    = info.cedula_evaluado    || "N/A";
    const cargoEvaluado     = info.cargo_evaluado     || "N/A";
    const ubicacionEval     = info.ubicacion_evaluado || "N/A";
  
    const nombreEvaluador   = info.nombre_evaluador   || "N/A";
    const cedulaEvaluador   = info.cedula_evaluador   || "N/A";
    const cargoEvaluador    = info.cargo_evaluador    || "N/A";
    const ubicacionEvaluador= info.ubicacion_evaluador|| "N/A";
  
    const nombreSupervisor  = info.nombre_supervisor  || "N/A";
    const cedulaSupervisor  = info.cedula_supervisor  || "N/A";
    const cargoSupervisor   = info.cargo_supervisor   || "N/A";
  
    const puntajeFinal      = info.puntaje_final      || "N/A";
    const rangoActuacion    = info.rango_actuacion    || "N/A";
    const comentarioSup     = info.comentario_supervisor || "";
    const comentarioEval    = info.comentario_evaluado   || "";
    const conformidad       = info.conformidad           || "";
  
    let y = 25;
    doc.setFontSize(12);
    doc.text("EVALUACION DEL DESEMPEÑO - NIVEL ADMINISTRATIVO", 105, 15, { align: "center" });
  
    // Sección Periodo
    doc.setFontSize(10);
    doc.text('PERIODO DE EVALUACION', 10, y); 
    y += 5;
    doc.autoTable({
      startY: y,
      head: [['Campo', 'Valor']],
      body: [
        ['Fecha Inicio', fechaInicio],
        ['Fecha Cierre', fechaCierre],
        ['Periodo', periodoEvaluado]
      ],
      styles: { fontSize: 8, cellPadding: 0.5 },
      margin: { left: 10 },
      columnStyles: { 0: { cellWidth: 30 }, 1: { cellWidth: 30 } }
    });
    y = doc.lastAutoTable.finalY + 8;
  
    // Sección A: Datos de identificación
    doc.setFontSize(10);
    doc.text('SECCION "A": DATOS DE IDENTIFICACION', 10, y); y += 5;
    doc.setFontSize(8);
    y += 6;
  
   // ============================================================
// TABLA 1 — DATOS DEL EVALUADO (UNA SOLA FILA)
// ============================================================

doc.setFontSize(10);
doc.text('DATOS DEL EVALUADO', 10, y);
y += 6;

doc.autoTable({
    startY: y,
    theme: 'grid',
    head: [[
        { content: 'Apellidos y Nombres', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Cédula', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Cargo', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Ubicación Administrativa', styles: { fontStyle: 'bold', halign: 'center' } }
    ]],
    body: [[
        ` ${info.nombre_evaluado}`,
        `${info.cedula_evaluado}`,
        `${info.cargo_evaluado}`,
        `${info.ubicacion_evaluado}`
    ]],
    styles: {
        fontSize: 7.5,
        cellPadding: 1,
        halign: 'center',
        lineWidth: 0.3
    },
    margin: { left: 10, right: 10 },
    tableWidth: 190
});

y = doc.lastAutoTable.finalY + 10;


// ============================================================
// TABLA 2 — DATOS DEL EVALUADOR (UNA SOLA FILA)
// ============================================================

doc.setFontSize(10);
doc.text('DATOS DEL EVALUADOR', 10, y);
y += 6;

doc.autoTable({
    startY: y,
    theme: 'grid',
    head: [[
        { content: 'Apellidos y Nombres', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Cédula', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Cargo', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Ubicación Administrativa', styles: { fontStyle: 'bold', halign: 'center' } }
    ]],
    body: [[
        `${info.nombre_evaluador}`,
        `${info.cedula_evaluador}`,
        `${info.cargo_evaluador}`,
        `${info.ubicacion_evaluador}`
    ]],
    styles: {
        fontSize: 7.5,
        cellPadding: 1,
        halign: 'center',
        lineWidth: 0.3
    },
    margin: { left: 10, right: 10 },
    tableWidth: 190
});

y = doc.lastAutoTable.finalY + 10;


// ============================================================
// TABLA 3 — DATOS DEL SUPERVISOR (UNA SOLA FILA)
// ============================================================

doc.setFontSize(10);
doc.text('DATOS DEL SUPERVISOR', 10, y);
y += 6;

doc.autoTable({
    startY: y,
    theme: 'grid',
    head: [[
        { content: 'Apellidos y Nombres', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Cédula', styles: { fontStyle: 'bold', halign: 'center' } },
        { content: 'Cargo', styles: { fontStyle: 'bold', halign: 'center' } }
    ]],
    body: [[
        `${info.nombre_supervisor}`,
        `${info.cedula_supervisor}`,
        `${info.cargo_supervisor}`
    ]],
    styles: {
        fontSize: 7.5,
        cellPadding: 1,
        halign: 'center',
        lineWidth: 0.3
    },
    margin: { left: 10, right: 10 },
    tableWidth: 190
});

y = doc.lastAutoTable.finalY + 10;

    // Sección B: Objetivos
    doc.setFontSize(10);
    doc.text('SECCION "B": OBJETIVOS', 10, y); y += 4;
    doc.autoTable({
      startY: y,
      head: [['Objetivo', 'Peso', 'Rango', 'PesoxRango']],
      body: objetivos.map(obj => [obj.nombre_objetivo, obj.peso_objetivo, obj.rango_obj, obj.pesoxrango_obj]),
      styles: { fontSize: 8, cellPadding: 1 },
      margin: { left: 10, right: 10 }
    });
    y = doc.lastAutoTable.finalY + 6;
  
    // Sección C: Competencias
    doc.setFontSize(10);
    doc.text('SECCION "C": COMPETENCIAS', 10, y); y += 4;
    doc.autoTable({
      startY: y,
      head: [['Competencia', 'Peso', 'Rango', 'PesoxRango']],
      body: competencias.map(comp => [comp.nombre_competencia, comp.peso_competencia, comp.rango_comp, comp.pesoxrango_comp]),
      styles: { fontSize: 8, cellPadding: 1 },
      margin: { left: 10, right: 10 }
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
      margin: { left: 10, right: 10 }
    });
    y = doc.lastAutoTable.finalY + 6;
  
    // Sección E: Comentarios
    doc.setFontSize(10);
    doc.text('SECCION "E": COMENTARIOS Y CONFORMIDAD', 10, y); y += 4;
    doc.autoTable({
      startY: y,
      head: [['Comentario Supervisor', 'Comentario Evaluado', 'Conformidad']],
      body: [[comentarioSup, comentarioEval, conformidad]],
      styles: { fontSize: 8, cellPadding: 1 },
      margin: { left: 10, right: 10 }
    });
  
    // Sección F: Firmas
    doc.setFontSize(8);
    let yFirmas = doc.lastAutoTable.finalY + 10;
    doc.text("Firma Evaluador", 30, yFirmas);
    doc.line(20, yFirmas + 8, 60, yFirmas + 8);
    doc.text("Firma Supervisor", 95, yFirmas);
    doc.line(85, yFirmas + 8, 145, yFirmas + 8);
    doc.text("Firma Evaluado", 160, yFirmas);
    doc.line(150, yFirmas + 8, 200, yFirmas + 8);
  }
  
  
  
  
  function generarPlanillaExcepcional(doc, info, motivos) {
    // Definir constantes al inicio
    const nombreEvaluado   = info.nombre_evaluado    || "N/A";
    const cedulaEvaluado   = info.cedula_evaluado    || "N/A";
    const cargoEvaluado    = info.cargo_evaluado     || "N/A";
    const ubicacionEval    = info.ubicacion_evaluado || "N/A";
    const periodoEvaluado  = info.periodo_evaluado   || "N/A";
    const fecha            = info.fecha_excep        || "N/A";
    const puntajeFinal     = info.puntaje_final      || "N/A";
    const rangoActuacion   = info.rango_actuacion    || "N/A";
  
    let y = 25;
    doc.setFontSize(12);
    doc.text("PLANILLA DE DESEMPEÑO EXCEPCIONAL", 105, 15, { align: "center" });
  
    // Sección A: Datos de identificación en tabla
    doc.setFontSize(10);
    doc.text('SECCION "A": DATOS DE IDENTIFICACION', 10, y); 
    y += 5;
  
    doc.autoTable({
      startY: y,
      head: [['Campo', 'Valor']],
      body: [
        ['Nombre', nombreEvaluado],
        ['Cédula', cedulaEvaluado],
        ['Cargo', cargoEvaluado],
        ['Ubicación', ubicacionEval],
        ['Periodo', periodoEvaluado],
        ['Puntaje Final', puntajeFinal],
        ['Rango de Actuación', rangoActuacion]
      ],
      styles: { fontSize: 8, cellPadding: 1 },
      margin: { left: 10, right: 10 }
    });
    y = doc.lastAutoTable.finalY + 8;
  
    // Sección B: Exposición de motivos en tabla
    doc.setFontSize(10);
    doc.text('SECCION "B": EXPOSICION DE MOTIVOS / ASIGNACION DE RANGO EXCEPCIONAL', 10, y); 
    y += 6;
  
    doc.autoTable({
      startY: y,
      head: [['Indicador', 'Motivo']],
      body: motivos.map(m => [
        m.indicador || "N/A",
        m.motivo    || "N/A"
      ]),
      styles: { fontSize: 8, cellPadding: 1 },
      margin: { left: 10, right: 10 }
    });
    y = doc.lastAutoTable.finalY + 10;
  
    // Sección C: Firmas (solo Evaluador y Evaluado)
    doc.setFontSize(8);
    doc.text("Firma Evaluador", 30, y);
    doc.line(20, y + 8, 60, y + 8);
  
    // En lugar de firma de supervisor → mostrar Fecha
    doc.text(`Fecha: ${fecha}`, 95, y);
  
    doc.text("Firma Evaluado", 160, y);
    doc.line(150, y + 8, 200, y + 8);
  }