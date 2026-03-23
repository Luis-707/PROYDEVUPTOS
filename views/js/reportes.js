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

    // Título principal (igual que original)
    doc.setFontSize(20);
    doc.text('Reportes de Evaluador', 20, 20);
    doc.setFontSize(12);
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')}`, 20, 30);

    let y = 50;

    // Título de sección como en el código original
    doc.setFontSize(10);
    doc.text('REPORTES DE EVALUADORES', 10, y);
    y += 4;

    // Obtener datos filtrados de DataTable
    const table = $('#tabla-reportes').DataTable();
    const filas = [];
    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text().trim() : '';
        }).get();
        filas.push(datosFila);
    });

    // AutoTable con EXACTO estilo de objetivos/competencias
    doc.autoTable({
        startY: y,
        head: [['CÉDULA', 'NOMBRE', 'CARGO', 'PERÍODO', 'AÑO', 'PUNTAJE', 'RANGO']],
        body: filas,
        styles: { 
            fontSize: 8, 
            cellPadding: 1 
        },
        margin: { left: 10, right: 10 },
        columnStyles: {
            0: { cellWidth: 17 },  // CÉDULA
            1: { cellWidth: 30 },  // NOMBRE  
            2: { cellWidth: 35 },  // CARGO
            3: { cellWidth: 25 },  // PERÍODO
            4: { cellWidth: 20 },  // AÑO
            5: { cellWidth: 20 },  // PUNTAJE
            6: { cellWidth: 40 }   // RANGO
        }
    });

    // Footer como en el original (después de tabla)
    y = doc.lastAutoTable.finalY + 6;
    doc.setFontSize(9);
    doc.setFont(undefined, 'italic');
    doc.text(`Total filas procesadas: ${filas.length}`, 20, y);
    doc.text(`Generado el: ${new Date().toLocaleString('es-ES')}`, 20, y + 6);

    doc.save('reportes_evaluador.pdf');
}