// =============================
// Validaciones básicas
// =============================
function validarCadena(cadena){
    var regex = /^[aA-zZàèìòùÁÉÍÓÚ\s]+$/;
    return regex.test(cadena);
  }
  
  function validarnumero(numero){
    var regex = /^[0-9]+$/;
    return regex.test(numero);
  }
  
  function validarcaracter(cadena){
    var regex = /^[0-9aA-zZàèìòùÁÉÍÓÚ_.-]+$/;
    return regex.test(cadena);
  }

function Validar_form_comentario_evaluado(opc) {
  var formulario = document.getElementById('form_comentario_evaluado');
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
        const regex = /^[0-9A-Za-zÁÉÍÓÚáéíóúñÑ.,;:()¿?¡!_\-\s]+$/;
        if (!regex.test(comentario)) {
          alert("El comentario contiene caracteres no permitidos.");
          isValid = false;
        }
      break;

      case 'conformidad':
        conformidad = valor;
      break;

      case 'id_eval_admin':
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

  // ❌ Validar id_eval_admin fuera del bucle
  if (!idEval) {
    alert("Error interno: No se encontró el ID de evaluación.");
    isValid = false;
  }

  if (isValid && opc === 1) {
    guardarComentarioEvaluado();
  }
}
    
  
  
  
    async function guardarComentarioEvaluado(){
  
      // antes de capturar los valores del formulario debes validarlos
  let datosPersona = capturarValoresFormulario('form_comentario_evaluado');
  
    try {
      const resp = await microApi('controlador/?g_comentario_evaluado', datosPersona);
  
      if (typeof resp === 'string' && resp.includes(' No Existe')) {
        Swal.fire({
          icon: 'error',
          title: 'Evaluado no encontrado',
          text: resp
        });
      } else {
        valorFormComentarioEval();
  
        Swal.fire({
          icon: 'success',
          title: 'Comentario añadido',
          text: 'El comentario del evaluado se actualizó con éxito'
        });
      }
    } catch (err) {
      console.error("Error actualizando evaluado:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al actualizar el evaluado'
      });
    }

    }

  function valorFormComentarioEval(ComentarioEval = "",idEvalEval="") {

    document.getElementById("comentario_evaluado").value = ComentarioEval;

  }