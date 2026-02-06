// =============================
// LISTAR REPORTES OBREROS
// =============================
async function listarReportesObreros() {
    try {
        const resp = await microApi('controlador/?listarReportesObreros');

        if (!resp || !resp.success) return;

        const data = Array.isArray(resp.data[0]) ? resp.data[0] : resp.data;
        const tbody = document.querySelector("#tabla-reportes-obrero tbody");
        tbody.innerHTML = "";

        data.forEach(item => {
            const cedula = item.cedula_usuario;
            const fullname = item.nombre_completo || "No encontrado";
            const cargo = item.cargo_evaluado || "Sin cargo";
            const anio = item.anio_inicio || "N/A";
            const periodo = item.periodo_evaluacion || "N/A";
            const conformidad = item.conformidad || "";

            const puedeDescargar =
                item.comentario_supervisor &&
                item.comentario_evaluado &&
                item.conformidad;

            let acciones = "";
            if (puedeDescargar) {
                acciones = `
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="generarPDFObrero(${item.id_eval_obreros})">
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

    } catch (error) {
        console.error("Error al listar reportes obreros:", error);
    }
}

// =============================
// GENERAR PDF OBRERO
// =============================
async function generarPDFObrero(idEvalObrero) {
    try {
        const resp = await microApi(`controlador/?datos_reportes_obrero&id_eval_obreros=${idEvalObrero}`);

        if (!resp || !resp.success) {
            console.error("Error en respuesta:", resp);
            return;
        }

        const info = resp.data || {};
        const factores = Array.isArray(resp.factores) ? resp.factores : [];
        const criterios = Array.isArray(resp.criterios) ? resp.criterios : [];
        const seleccionados = Array.isArray(resp.seleccionados) ? resp.seleccionados : [];

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        generarPlanillaObrera(doc, info, factores, criterios, seleccionados);

        doc.save(`Evaluacion_Obrero_${info.cedula_evaluado}.pdf`);

    } catch (error) {
        console.error("Error al generar PDF obrero:", error);
    }
}

// =============================
// GENERAR PLANILLA OBRERA (PDF)
// =============================
function generarPlanillaObrera(doc, info, factores, criterios, seleccionados) {

    const seleccionadosMap = {};
    seleccionados.forEach(s => {
        seleccionadosMap[s.criterio_id] = s.puntaje_obtenido;
    });

    let y = 20;

    // Título
    doc.setFontSize(12);
    doc.text("EVALUACION DEL DESEMPEÑO - NIVEL OBRERO", 105, y, { align: "center" });
    y += 10;

    // =============================
    // SECCIÓN A: DATOS DEL EVALUADO
    // =============================
    doc.setFontSize(10);
    doc.text('SECCION "A": DATOS DEL EVALUADO', 10, y);
    y += 5;

    doc.setFontSize(8);
    doc.text(`Evaluado: ${info.nombre_evaluado}`, 10, y); y += 4;
    doc.text(`Cédula: ${info.cedula_evaluado}`, 10, y); y += 4;
    doc.text(`Cargo: ${info.cargo_evaluado}`, 10, y); y += 4;
    doc.text(`Ubicación Administrativa: ${info.ubicacion_evaluado}`, 10, y); y += 4;
    doc.text(`Área Ocupacional: ${info.area_ocupacional}`, 10, y); y += 4;
    doc.text(`Ubicación Física: ${info.ubicacion_fisica}`, 10, y); y += 4;
    doc.text(`Años en el Puesto: ${info.tiempo_puesto}`, 10, y); y += 8;

    // =============================
    // SECCIÓN B: DATOS DEL EVALUADOR
    // =============================
    doc.setFontSize(10);
    doc.text('SECCION "B": DATOS DEL EVALUADOR', 10, y);
    y += 5;

    doc.setFontSize(8);
    doc.text(`Evaluador: ${info.nombre_evaluador}`, 10, y); y += 4;
    doc.text(`Cédula: ${info.cedula_evaluador}`, 10, y); y += 4;
    doc.text(`Cargo: ${info.cargo_evaluador}`, 10, y); y += 4;
    doc.text(`Ubicación Administrativa: ${info.ubicacion_evaluador}`, 10, y); y += 8;

    // =============================
    // SECCIÓN C: FACTORES Y CRITERIOS
    // =============================
    doc.setFontSize(10);
    doc.text('SECCION "C": FACTORES Y CRITERIOS', 10, y);
    y += 4;

    const tabla = [];

    factores.forEach(f => {
        tabla.push([{ content: f.nombre_factor, colSpan: 4, styles: { fillColor: [220, 220, 220] } }]);

        const criteriosFactor = criterios.filter(c => c.factor_id == f.factor_id);

        criteriosFactor.forEach(c => {
            const seleccionado = seleccionadosMap[c.criterio_id] !== undefined;
            tabla.push([
                seleccionado ? "[x] " + c.codigo_criterio : c.codigo_criterio,
                c.descripcion_criterio,
                c.valor_criterio,
                seleccionado ? seleccionadosMap[c.criterio_id] : "-"
            ]);
        });
    });

    doc.autoTable({
        startY: y,
        head: [['Código', 'Criterio', 'Valor', 'Puntaje']],
        body: tabla,
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 }
    });

    y = doc.lastAutoTable.finalY + 8;

    // =============================
    // SECCIÓN D: RESULTADO FINAL
    // =============================
    doc.setFontSize(10);
    doc.text('SECCION "D": RESULTADO FINAL', 10, y);
    y += 4;

    doc.autoTable({
        startY: y,
        head: [['Puntaje Final', 'Rango']],
        body: [[info.puntaje_total, info.nombre_rango]],
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 }
    });

    y = doc.lastAutoTable.finalY + 8;

    // =============================
    // SECCIÓN E: COMENTARIOS
    // =============================
    doc.setFontSize(10);
    doc.text('SECCION "E": COMENTARIOS Y CONFORMIDAD', 10, y);
    y += 4;

    doc.autoTable({
        startY: y,
        head: [['Comentario Supervisor', 'Comentario Evaluado', 'Conformidad']],
        body: [[info.comentario_supervisor, info.comentario_evaluado, info.conformidad]],
        styles: { fontSize: 8, cellPadding: 1 },
        margin: { left: 10, right: 10 }
    });

    // =============================
// SECCIÓN F: FIRMAS
// =============================
// =============================
// SECCIÓN F: FIRMAS
// =============================
let yFirmas = doc.lastAutoTable.finalY + 12;
doc.setFontSize(8);

// --- Firmas ---
doc.text("Firma Evaluador", 30, yFirmas);
doc.line(20, yFirmas + 8, 70, yFirmas + 8);

doc.text("Firma Supervisor", 95, yFirmas);
doc.line(85, yFirmas + 8, 135, yFirmas + 8);

doc.text("Firma Evaluado", 160, yFirmas);
doc.line(150, yFirmas + 8, 200, yFirmas + 8);

// =============================
// TABLAS DE FECHAS (3 tablas pequeñas separadas)
// =============================
// =============================
// TABLAS DE FECHAS (3 tablas separadas, cada una con 3 columnas)
// =============================
let yFechas = yFirmas + 20;

// Tabla 1: Fecha Evaluador
doc.autoTable({
    startY: yFechas,
    head: [[{ content: 'Fecha Evaluador', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } }]],
    body: [
        ['', '', ''] // fila vacía con 3 columnas
    ],
    theme: 'grid',
    styles: {
        fontSize: 7,
        cellPadding: 1,
        halign: 'center',
        textColor: 0,
        lineColor: 0,
        lineWidth: 0.3
    },
    headStyles: {
        fillColor: false,
        textColor: 0
    },
    margin: { left: 20 },
    tableWidth: 50
});

// Tabla 2: Fecha Supervisor
doc.autoTable({
    startY: yFechas,
    head: [[{ content: 'Fecha Supervisor', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } }]],
    body: [
        ['', '', '']
    ],
    theme: 'grid',
    styles: {
        fontSize: 7,
        cellPadding: 1,
        halign: 'center',
        textColor: 0,
        lineColor: 0,
        lineWidth: 0.3
    },
    headStyles: {
        fillColor: false,
        textColor: 0
    },
    margin: { left: 85 },
    tableWidth: 50
});

// Tabla 3: Fecha Evaluado
doc.autoTable({
    startY: yFechas,
    head: [[{ content: 'Fecha Evaluado', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } }]],
    body: [
        ['', '', '']
    ],
    theme: 'grid',
    styles: {
        fontSize: 7,
        cellPadding: 1,
        halign: 'center',
        textColor: 0,
        lineColor: 0,
        lineWidth: 0.3
    },
    headStyles: {
        fillColor: false,
        textColor: 0
    },
    margin: { left: 150 },
    tableWidth: 50
});
}