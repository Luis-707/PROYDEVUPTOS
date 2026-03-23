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

    let comentario = "";
    let conformidad = "";
    let idEval = "";

    for (let [key, valor] of Data.entries()) {

        if (!valor) {
            console.warn(`⚠️ El campo ${key} está vacío`);
        }

        switch (key) {

            case 'comentario_evaluado':
                comentario = valor.trim();

                // ❌ Comentario vacío
                if (comentario === "") {
                    alert("Debe escribir un comentario antes de guardar.");
                    isValid = false;
                }

                // ❌ Longitud mínima
                if (comentario.length < 10) {
                    alert("El comentario debe tener al menos 10 caracteres.");
                    isValid = false;
                }

                // ❌ Comentarios triviales
                const triviales = ["ok", "bien", "si", "no", ".", "na", "n/a"];
                if (triviales.includes(comentario.toLowerCase())) {
                    alert("El comentario es demasiado corto o no aporta información.");
                    isValid = false;
                }

                // ❌ Caracteres permitidos
                const regexEval = /^[0-9A-Za-zÁÉÍÓÚáéíóúñÑ.,;:()¿?¡!_\-\s]+$/;
                if (!regexEval.test(comentario)) {
                    alert("El comentario contiene caracteres no permitidos.");
                    isValid = false;
                }
            break;

            case 'conformidad':
                conformidad = valor;
            break;

            case 'id_eval_obreros':
                idEval = valor;
            break;

            default:
            break;
        }
    }

    // ❌ Validar conformidad fuera del bucle
    if (!conformidad) {
        alert("Debe seleccionar si está de acuerdo con la evaluación (Sí o No).");
        isValid = false;
    }

    // ❌ Validar id_eval_obreros fuera del bucle
    if (!idEval) {
        alert("Error interno: No se encontró el ID de evaluación.");
        isValid = false;
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
