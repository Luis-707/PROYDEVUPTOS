// =============================
// LISTAR REPORTE DEL EVALUADOR
// =============================
async function listarReporte() {

    try {
        const resp = await microApi('controlador/?reportes_desemp');

        console.log("Respuesta del backend:", resp);

        // Validar respuesta
        if (!resp || resp.success !== true) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp?.message || 'No se pudo obtener el reporte'
            });
            return;
        }

        // Validar que resp.data sea un array
        if (!Array.isArray(resp.data)) {
            console.error("El backend no devolvió un array:", resp.data);
            Swal.fire({
                icon: 'warning',
                title: 'Datos inválidos',
                text: 'El servidor no devolvió datos válidos para el reporte'
            });
            return;
        }

        // ✔ Aquí estaba el error: ahora enviamos SOLO el array
        listarTablaReportes(resp.data);

    } catch (error) {
        console.error("Error inesperado:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'Ocurrió un error al cargar el reporte'
        });
    }
}



// =============================
// LISTAR TABLA DE REPORTES
// =============================
function listarTablaReportes(datos) {

    // Aplanar si viene como array anidado
    datos = Array.isArray(datos[0]) ? datos.flat() : datos;

    $('#tabla-reportes').DataTable({
        destroy: true,
        data: datos,
        columns: [
            { data: 'cedula_usuario', title: 'Cédula' },
            { data: 'nombre_completo', title: 'Nombre' },
            { data: 'cargo_evaluado', title: 'Cargo' },
            { data: 'periodo_evaluado', title: 'Periodo' },
            { data: 'anio_inicio', title: 'Año Inicio' },
            { data: 'puntaje_final', title: 'Puntaje Final' },
            { data: 'rango_actuacion', title: 'Rango Actuación' }
        ],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros por página",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            paginate: {
                previous: "Anterior",
                next: "Siguiente"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                text: '🧹 Limpiar Filtro',
                action: function (e, dt, node, config) {
                    dt.search('').draw();
                    dt.columns().search('').draw();
                }
            }
        ]
    });
}



// =============================
// GENERAR PDF DEL REPORTE
// =============================
function generarPDFReportes() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Título principal
    doc.setFontSize(20);
    doc.text('Reportes de Evaluador', 20, 20);
    doc.setFontSize(12);
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')}`, 20, 30);

    let y = 50;

    // === ENCABEZADOS DE TABLA ===
    doc.setFontSize(11);
    doc.setFont(undefined, 'bold');
    
    const encabezados = [
        { texto: 'CÉDULA', x: 18, ancho: 17 },
        { texto: 'NOMBRE', x: 35, ancho: 30 },
        { texto: 'CARGO', x: 65, ancho: 35 },
        { texto: 'PERÍODO', x: 100, ancho: 20 },
        { texto: 'AÑO', x: 120, ancho: 20 },
        { texto: 'PUNTAJE', x: 140, ancho: 20 },
        { texto: 'RANGO', x: 160, ancho: 20 }
    ];

    const altoEncabezado = 8;
    doc.setFillColor(240, 240, 240);
    doc.rect(15, y, 180, altoEncabezado, 'F');
    doc.rect(15, y, 180, altoEncabezado);

    encabezados.forEach(enc => {
        const textoEnc = doc.splitTextToSize(enc.texto, enc.ancho);
        textoEnc.forEach((line, i) => 
            doc.text(line, enc.x, y + 6 + i * 5)
        );
    });

    y += altoEncabezado + 2;

    let filasPDF = 0;
    const table = $('#tabla-reportes').DataTable();

    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text().trim() : '';
        }).get();

        const cedula = doc.splitTextToSize(datosFila[0], 16);
        const nombre = doc.splitTextToSize(datosFila[1], 28);
        const cargo = doc.splitTextToSize(datosFila[2], 32);
        const periodo = doc.splitTextToSize(datosFila[3], 18);
        const anio = doc.splitTextToSize(datosFila[4], 18);
        const puntaje = doc.splitTextToSize(datosFila[5], 18);
        const rango = doc.splitTextToSize(datosFila[6], 18);

        const altoFila = Math.max(
            cedula.length, nombre.length, cargo.length, 
            periodo.length, anio.length, puntaje.length, rango.length
        ) * 5 + 2;

        if (y + altoFila > 260) {
            doc.addPage();
            y = 50;

            doc.setFillColor(240, 240, 240);
            doc.rect(15, y, 180, altoEncabezado, 'F');
            doc.rect(15, y, 180, altoEncabezado);

            encabezados.forEach(enc => {
                const textoEnc = doc.splitTextToSize(enc.texto, enc.ancho);
                textoEnc.forEach((line, i) => 
                    doc.text(line, enc.x, y + 6 + i * 5)
                );
            });

            y += altoEncabezado + 5;
        }

        if (filasPDF % 2 === 0) {
            doc.setFillColor(248, 248, 248);
            doc.rect(15, y, 180, altoFila, 'F');
        }
        doc.rect(15, y, 180, altoFila);

        doc.setFont(undefined, 'normal');
        doc.setFontSize(9);

        cedula.forEach((line, i) => doc.text(line, 18, y + 6 + i * 5));
        nombre.forEach((line, i) => doc.text(line, 35, y + 6 + i * 5));
        cargo.forEach((line, i) => doc.text(line, 65, y + 6 + i * 5));
        periodo.forEach((line, i) => doc.text(line, 100, y + 6 + i * 5));
        anio.forEach((line, i) => doc.text(line, 120, y + 6 + i * 5));
        puntaje.forEach((line, i) => doc.text(line, 140, y + 6 + i * 5));
        rango.forEach((line, i) => doc.text(line, 160, y + 6 + i * 5));

        y += altoFila;
        filasPDF++;
    });

    const paginaFinalY = y + 15;
    doc.setFontSize(9);
    doc.setFont(undefined, 'italic');
    doc.text(`Total filas procesadas: ${filasPDF}`, 20, paginaFinalY);
    doc.text(`Generado el: ${new Date().toLocaleString('es-ES')}`, 20, paginaFinalY + 6);

    doc.save('reportes_evaluador.pdf');
}