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
  
    for (let [key, valor] of Data.entries()) {
        if (!valor) {
          console.warn(`⚠️ El campo ${key} está vacío`);
        }
    
        switch (key) {
          case 'comentario_evaluado':
            if (!validarCadena(valor)) {
              alert("El comentario del supervisor no debe tener caracteres Especiales diferentes a ( _  .  -  ) ");
              isValid = false; // Marca como inválido
            }
            break;

            default:
            // No hacer nada para otros campos
            break;
        }
      }
    
      if (isValid) {
        if (opc === 1) {
            guardarComentarioEvaluado();
          }
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