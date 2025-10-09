//alerta.js

// ✅ Alerta de éxito
function mostrarAlertaExito(mensaje, titulo = 'Éxito') {
    Swal.fire({
        icon: 'success',
        title: titulo,
        text: mensaje,
        confirmButtonColor: '#28a745'
    });
}

// ⚠️ Alerta de advertencia
function mostrarAlertaAdvertencia(mensaje, titulo = 'Atención') {
    Swal.fire({
        icon: 'warning',
        title: titulo,
        text: mensaje,
        confirmButtonColor: '#f39c12'
    });
}

// ❌ Alerta de error
function mostrarAlertaError(mensaje, titulo = 'Error') {
    Swal.fire({
        icon: 'error',
        title: titulo,
        text: mensaje,
        confirmButtonColor: '#e74c3c'
    });
}

// ℹ️ Alerta de información
function mostrarAlertaInfo(mensaje, titulo = 'Información') {
    Swal.fire({
        icon: 'info',
        title: titulo,
        text: mensaje,
        confirmButtonColor: '#17a2b8'
    });
}