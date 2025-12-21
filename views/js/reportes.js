async function listarReporte() {
    var resp = await microApi('controlador/?reportes_desemp'); // Asume que la API devuelve los datos de la consulta
    listarTablaReportes(resp);
}

/*function listarTablaReportes(datos) {
    // Aplanar el array si viene anidado
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
                extend: 'pdfHtml5',
                title: 'Reportes de Evaluación',
                messageTop: 'Reportes de Evaluación',
                customize: function (doc) {
                    // Agregar fecha en el encabezado
                    var now = new Date();
                    var dateStr = now.toLocaleDateString('es-ES');
                    doc.content.unshift({
                        text: 'Fecha de generación: ' + dateStr,
                        style: 'header'
                    });
                }
            }
        ]
    });
}*/

function listarTablaReportes(datos) {
    // Aplanar el array si viene anidado
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
            },
            buttons: {
                clearFilter: "Limpiar Filtro"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                text: '🧹 Limpiar Filtro',
                action: function (e, dt, node, config) {
                    // Limpiar búsqueda
                    dt.search('').draw();
                    
                    // Resetear columnas si tienen filtros individuales
                    dt.columns().search('').draw();
                    
                    // Mostrar mensaje de confirmación
                    //alert('✅ Filtros limpiados. Se muestran todos los registros.');
                }
            }
        ]
    });
}


/*function generarPDFReportes() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Título
    doc.setFontSize(20);
    doc.text('Reportes de Evaluador', 20, 20);
    doc.setFontSize(12);
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')}`, 20, 30);

    let y = 50;
    let filasPDF = 0;

    // Obtener filas visibles (respetando filtro actual)
    const table = $('#tabla-reportes').DataTable();
    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text() : '';
        }).get();

        if (filasPDF > 0 && y > 270) {
            doc.addPage();
            y = 20;
        }

        // Tabla en PDF
        doc.setFontSize(10);
        doc.rect(15, y, 180, 8); // Borde fila
        doc.text(datosFila[0], 20, y + 6); // Cédula
        doc.text(datosFila[1], 35, y + 6); // Nombre
        doc.text(datosFila[2], 65, y + 6); // Cargo
        doc.text(datosFila[3], 105, y + 6); // Periodo
        doc.text(datosFila[4], 125, y + 6); // Año
        doc.text(datosFila[5], 155, y + 6); // Puntaje Final
        doc.text(datosFila[6], 170, y + 6); // Rango Actuación

        y += 10;
        filasPDF++;
    });

    // Footer
    doc.setFontSize(9);
    doc.text(`Total filas: ${filasPDF}`, 20, y + 10);

    // Descargar
    doc.save('reportes_evaluador.pdf');
}
*/

/*function generarPDFReportes() {
    // Asegúrate de que la librería jsPDF esté cargada correctamente
    if (!window.jspdf) {
        console.error('jsPDF no está disponible. Verifica que el script esté cargado.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Título
    doc.setFontSize(20);
    doc.text('Reportes de Evaluador', 20, 20);
    doc.setFontSize(12);
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')}`, 20, 30);

    let y = 50;
    let filasPDF = 0;

    // Obtener filas visibles (respetando filtro actual)
    const table = $('#tabla-reportes').DataTable();
    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text() : '';
        }).get();

        if (filasPDF > 0 && y > 270) {
            doc.addPage();
            y = 20;
        }

        // Tabla en PDF
        doc.setFontSize(10);
        doc.rect(15, y, 180, 8); // Borde fila
        doc.text(datosFila[0], 20, y + 6); // Cédula
        doc.text(datosFila[1], 35, y + 6); // Nombre
        doc.text(datosFila[2], 65, y + 6); // Cargo
        doc.text(datosFila[3], 105, y + 6); // Periodo
        doc.text(datosFila[4], 125, y + 6); // Año
        doc.text(datosFila[5], 155, y + 6); // Puntaje Final
        doc.text(datosFila[6], 170, y + 6); // Rango Actuación

        y += 10;
        filasPDF++;
    });

    // Footer
    doc.setFontSize(9);
    doc.text(`Total filas: ${filasPDF}`, 20, y + 10);

    // Descargar
    doc.save('reportes_evaluador.pdf');
}
*/

/*function generarPDFReportes() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Título
    doc.setFontSize(20);
    doc.text('Reportes de Evaluador', 20, 20);
    doc.setFontSize(12);
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')}`, 20, 30);

    let y = 50;
    let filasPDF = 0;

    // Obtener filas visibles (respetando filtro actual)
    const table = $('#tabla-reportes').DataTable();
    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text() : '';
        }).get();

        if (filasPDF > 0 && y > 270) {
            doc.addPage();
            y = 20;
        }

        // Ajustar tamaño de fuente y posiciones para evitar solapamientos
        doc.setFontSize(10);
        doc.rect(15, y, 180, 8); // Borde fila

        // Columnas con posiciones y anchos ajustados
        doc.text(datosFila[0], 18, y + 6);  // Cédula
        doc.text(datosFila[1], 35, y + 6);  // Nombre
        doc.text(datosFila[2], 65, y + 6);  // Cargo
        doc.text(datosFila[3], 100, y + 6); // Periodo
        doc.text(datosFila[4], 120, y + 6); // Año
        doc.text(datosFila[5], 140, y + 6); // Puntaje Final
        doc.text(datosFila[6], 160, y + 6); // Rango Actuación

        y += 10;
        filasPDF++;
    });

    // Footer
    doc.setFontSize(9);
    doc.text(`Total filas: ${filasPDF}`, 20, y + 10);

    // Descargar
    doc.save('reportes_evaluador.pdf');
}
*/

/*function generarPDFReportes() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Título
    doc.setFontSize(20);
    doc.text('Reportes de Evaluador', 20, 20);
    doc.setFontSize(12);
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')}`, 20, 30);

    let y = 50;
    let filasPDF = 0;

    const table = $('#tabla-reportes').DataTable();
    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text() : '';
        }).get();

        // Dividir el texto largo en varias líneas
        const cedula = doc.splitTextToSize(datosFila[0], 20);
        const nombre = doc.splitTextToSize(datosFila[1], 25);
        const cargo = doc.splitTextToSize(datosFila[2], 25);
        const periodo = doc.splitTextToSize(datosFila[3], 25);
        const anio = doc.splitTextToSize(datosFila[4], 15);
        const puntaje = doc.splitTextToSize(datosFila[5], 20);
        const rango = doc.splitTextToSize(datosFila[6], 30);

        // Calcular el alto necesario para la fila
        const altoFila = Math.max(cedula.length, nombre.length, cargo.length, periodo.length, anio.length, puntaje.length, rango.length) * 5;

        // Salto de página si es necesario
        if (y + altoFila > 270) {
            doc.addPage();
            y = 20;
        }

        // Dibujar borde de la fila
        doc.rect(15, y, 180, altoFila);

        // Dibujar texto en varias líneas
        doc.setFontSize(10);
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

    // Footer
    doc.setFontSize(9);
    doc.text(`Total filas: ${filasPDF}`, 20, y + 10);

    // Descargar
    doc.save('reportes_evaluador.pdf');
}
*/

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
    doc.setFont(undefined, 'bold'); // Negrita para encabezados
    
    const encabezados = [
        { texto: 'CÉDULA', x: 18, ancho: 17 },
        { texto: 'NOMBRE', x: 35, ancho: 30 },
        { texto: 'CARGO', x: 65, ancho: 35 },
        { texto: 'PERÍODO', x: 100, ancho: 20 },
        { texto: 'AÑO', x: 120, ancho: 20 },
        { texto: 'PUNTAJE', x: 140, ancho: 20 },
        { texto: 'RANGO', x: 160, ancho: 20 }
    ];

    // Dibujar encabezados con fondo gris
    const altoEncabezado = 8;
    doc.setFillColor(240, 240, 240);
    doc.rect(15, y, 180, altoEncabezado, 'F');
    doc.rect(15, y, 180, altoEncabezado); // Borde
    
    encabezados.forEach(enc => {
        const textoEnc = doc.splitTextToSize(enc.texto, enc.ancho);
        textoEnc.forEach((line, i) => 
            doc.text(line, enc.x, y + 6 + i * 5)
        );
    });

    y += altoEncabezado + 2; // Espacio después de encabezados

    let filasPDF = 0;
    const table = $('#tabla-reportes').DataTable();

    table.rows({ search: 'applied' }).every(function () {
        const datosFila = $(this.node()).find('td').map(function (i) {
            return i < 7 ? $(this).text().trim() : '';
        }).get();

        // Anchos optimizados para evitar superposiciones
        const cedula = doc.splitTextToSize(datosFila[0], 16);  // Reducido
        const nombre = doc.splitTextToSize(datosFila[1], 28);
        const cargo = doc.splitTextToSize(datosFila[2], 32);
        const periodo = doc.splitTextToSize(datosFila[3], 18);
        const anio = doc.splitTextToSize(datosFila[4], 18);
        const puntaje = doc.splitTextToSize(datosFila[5], 18);
        const rango = doc.splitTextToSize(datosFila[6], 18);

        // Alto fila basado en el texto más largo
        const altoFila = Math.max(
            cedula.length, nombre.length, cargo.length, 
            periodo.length, anio.length, puntaje.length, rango.length
        ) * 5 + 2;

        // Salto de página mejorado
        if (y + altoFila > 260) {
            doc.addPage();
            y = 50;
            
            // Redibujar encabezados en nueva página
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

        // Borde fila con fondo alternado
        if (filasPDF % 2 === 0) {
            doc.setFillColor(248, 248, 248); // Gris claro alternado
            doc.rect(15, y, 180, altoFila, 'F');
        }
        doc.rect(15, y, 180, altoFila); // Borde siempre

        // Texto fila (fuente normal)
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

    // Footer mejorado
    const paginaFinalY = y + 15;
    doc.setFontSize(9);
    doc.setFont(undefined, 'italic');
    doc.text(`Total filas procesadas: ${filasPDF}`, 20, paginaFinalY);
    doc.text(`Generado el: ${new Date().toLocaleString('es-ES')}`, 20, paginaFinalY + 6);

    doc.save('reportes_evaluador.pdf');
}
