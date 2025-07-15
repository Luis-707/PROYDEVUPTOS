

function desplazamientoVista(idDragArea,idColumnaIzquierda){

    
    const dragArea = document.getElementById(idDragArea,);
    const columnaIzquierda = document.getElementById(idColumnaIzquierda);
    let isDragging = false;
    let startX, startWidth;

    // Evento para iniciar el arrastre
    dragArea.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.clientX; // Posición inicial del mouse en X
        startWidth = columnaIzquierda.offsetWidth; // Ancho inicial de la columna
        document.body.style.userSelect = 'none'; // Evitar selección de texto durante el arrastre
    });

    // Evento para detener el arrastre
    document.addEventListener('mouseup', () => {
        isDragging = false;
        document.body.style.userSelect = 'auto'; // Restaurar selección de texto
    });

    // Evento para mover el mouse
    document.addEventListener('mousemove', (e) => {
        if (isDragging) {
            const deltaX = e.clientX - startX; // Diferencia en la posición del mouse
            const newWidth = startWidth + deltaX; // Nuevo ancho de la columna

            // Limitar el ancho mínimo y máximo
            const minWidth = 200; // Ancho mínimo de la columna
            const maxWidth = window.innerWidth * 0.8; // Ancho máximo (80% del ancho de la ventana)

            if (newWidth >= minWidth && newWidth <= maxWidth) {
                columnaIzquierda.style.width = `${newWidth}px`; // Actualizar el ancho de la columna
            }
        }
    });
}
