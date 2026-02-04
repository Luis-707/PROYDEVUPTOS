// =============================
// Validaciones básicas
// =============================
function validarComentarioObrero(texto) {
    // Permite letras, números, espacios y . , - _ (comentarios reales)
    const regex = /^[0-9A-Za-zÁÉÍÓÚáéíóúñÑ\s.,_-]+$/;
    return regex.test(texto);
}

// =============================
// Validar formulario del evaluado
// =============================
function Validar_form_comentario_evaluado_obrero(opc) {

    const formulario = document.getElementById('form_comentario_evaluado_obrero');
    const Data = new FormData(formulario);
    let isValid = true;

    for (let [key, valor] of Data.entries()) {

        if (!valor) {
            console.warn(`⚠️ El campo ${key} está vacío`);
        }

        switch (key) {
            case 'comentario_evaluado':
                if (!validarComentarioObrero(valor)) {
                    alert("El comentario contiene caracteres no permitidos.");
                    isValid = false;
                }
                break;
        }
    }

    if (isValid && opc === 1) {
        guardarComentarioEvaluadoObrero();
    }
}

// =============================
// Guardar comentario del evaluado
// =============================
async function guardarComentarioEvaluadoObrero() {

    let datos = capturarValoresFormulario('form_comentario_evaluado_obrero');

    try {
        const resp = await microApi('controlador/?g_comentario_evaluado_obrero', datos);

        if (!resp?.success) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp.message || 'No se pudo guardar el comentario'
            });
            return;
        }

        valorFormComentarioEvalObrero();

        Swal.fire({
            icon: 'success',
            title: 'Comentario guardado',
            text: 'El comentario del evaluado obrero se actualizó con éxito'
        });

    } catch (err) {
        console.error("Error guardando comentario:", err);
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'Ocurrió un error al guardar el comentario'
        });
    }
}

// =============================
// Actualizar campo visual
// =============================
function valorFormComentarioEvalObrero(texto = "") {
    document.getElementById("comentario_evaluado").value = texto;
}
